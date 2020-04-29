<?php

namespace Modules\Web\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Modules\Domain\Traits\ResolveDomain;
use Modules\Web\Models\Webspace;
use Symfony\Component\Process\Process;
use function Safe\chown;
use function Safe\chgrp;

class CreateWebspace implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ResolveDomain;

    public $tries = 3;

    /**
     * @var Webspace
     */
    private $webspace;

    /**
     * Create a new job instance.
     *
     * @param  Webspace  $webspace
     */
    public function __construct(Webspace $webspace)
    {
        $this->webspace = $webspace;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $systemUser = $this->webspace->systemUser()->first();

        $paths = [
            $this->webspace->web_root,
            $this->webspace->document_root,
            $this->webspace->web_root.config('web-module.logs_dir'),
            $this->webspace->web_root.config('web-module.ssl_dir'),
            $this->webspace->web_root.config('web-module.tmp_dir'),
        ];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path);
            }
            chown($path, $systemUser->user);
            chgrp($path, $systemUser->group);
        }

        File::put($this->webspace->document_root.'index.html', "I'm working!");
        chown($this->webspace->document_root.'index.html', $systemUser->user);
        chgrp($this->webspace->document_root.'index.html', $systemUser->group);

        $ips = [];

        $ipv4 = $this->webspace->ipv4()->first();
        if ($ipv4 !== null) {
            $ips["$ipv4->address:80"] = false;
            if ($this->webspace->ssl_enabled) {
                $ips["$ipv4->address:443"] = true;
            }
        }

        $ipv6 = $this->webspace->ipv6()->first();
        if ($ipv6 !== null) {
            $ips["$ipv6->address:80"] = false;
            if ($this->webspace->ssl_enabled) {
                $ips["$ipv6->address:443"] = true;
            }
        }

        $fullDomain = $this->getFullDomain($this->webspace->domain()->first());

        File::put("/etc/apache2/sites-available/100-{$fullDomain}.conf", View::make('web::templates.apache.virtualhost', [
            'siteDir' => $this->webspace->web_root,
            'hasHttpsVhost' => $this->webspace->ssl_enabled,
            'ips' => $ips,
            'documentRoot' => $this->webspace->document_root,
            'user' => $systemUser->user,
            'group' => $systemUser->group,
            'domain' => $fullDomain,
            'phpMode' => null, // TODO: change
            'logDir' => $this->webspace->web_root.config('web-module.logs_dir'),
        ]));

        Process::fromShellCommandline("a2ensite 100-{$fullDomain}")->run();
        Process::fromShellCommandline("systemctl reload apache2")->run();
    }
}
