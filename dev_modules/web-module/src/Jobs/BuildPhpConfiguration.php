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

class BuildPhpConfiguration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ResolveDomain;

    /**
     * @var Webspace
     */
    private $webspace;

    /**
     * Create a new job instance.
     *
     * @return void
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
        //TODO: fail if php is not installed
        $systemUser = $this->webspace->systemUser()->first();

        $webspace = "webspace{$this->webspace->id}";
        File::put("/etc/php/7.3/fpm/pool.d/{$webspace}.conf", View::make('web::templates.fpm.pool', [
            'webspace' => $webspace,
            'listenMode' => 'unix', //TODO: make it selectable
            'unixSocketPath' => "/var/run/php/{$webspace}.sock", //TODO: make this path configurable
            'unixSocketOwner' => 'www-data', //TODO: check if it's actually the webserver user
            'unixSocketGroup' => $systemUser->group,
            'user' => $systemUser->user,
            'group' => $systemUser->group,
            'tempDir' => $this->webspace->web_root.config('web-module.tmp_dir'),
        ])); //TODO: make more options configurable

        Process::fromShellCommandline("systemctl reload php7.3-fpm")->run();
    }
}
