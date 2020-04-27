<?php

namespace Modules\Web\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Web\Models\Webspace;

class CreateWebspace implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
        $systemUser = $this->webspace->systemUser();
        dd($systemUser, $this->webspace->domain());
    }
}
