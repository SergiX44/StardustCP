<?php

namespace Modules\Web\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or upgrade the web module.';

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

        $pkg = $env->getPackageManager();
        $pkg->update();

        $this->installWebServer($pkg);
        $this->configureWebServer($pkg);

        return 0;
    }

    protected function installWebServer(?IPackageManager $pkg)
    {
        $this->info('Installing Apache and packages...');
        if (!$pkg->install(['apache2'])) {
            $this->error('Error during Apache installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
        $this->warn('Done.');
    }

    protected function configureWebServer(?IPackageManager $pkg)
    {
    }
}