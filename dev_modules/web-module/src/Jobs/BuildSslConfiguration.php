<?php

namespace Modules\Web\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Domain\Traits\ResolveDomain;
use Modules\Web\Models\Webspace;

class BuildSslConfiguration implements ShouldQueue
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
        //
    }
}
