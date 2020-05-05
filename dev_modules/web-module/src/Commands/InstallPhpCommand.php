<?php

namespace Modules\Web\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Core\Models\IP;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class InstallPhpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:php {version=vendor}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable PHP support.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Safe\Exceptions\FilesystemException
     */
    public function handle()
    {
        if (posix_getuid() !== 0) {
            $this->line('You must run the installer as root.');
            return 1;
        }

        $env = OS::recognize();

        $result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?", true);
        if (!$result) {
            $this->error('Cannot detect you system.');
            return 1;
        }

        //TODO: select php version to install

        $pkg = $env->getPackageManager();
        $pkg->update();

        if ($this->argument('version') === 'vendor') {
            $this->installFromOSVendor($pkg);
        } else {
            $this->error('The selected version could not be installed.');
            return 1;
        }

        $this->info('PHP support updated!');
        return 0;
    }

    private function installFromOSVendor(IPackageManager $pkg)
    {
        if (!$pkg->install(['php7.3-fpm'])) {
            $this->error('Error during php-fpm installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
    }
}
