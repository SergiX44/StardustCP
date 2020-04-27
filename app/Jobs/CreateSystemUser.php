<?php

namespace Core\Jobs;

use Core\Models\SystemUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class CreateSystemUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SystemUser
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param  SystemUser  $user
     */
    public function __construct(SystemUser $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $u = $this->user;
        Process::fromShellCommandline("useradd --system --shell /bin/false --create-home --user-group --home-dir $u->home_dir $u->user")->run();
    }
}
