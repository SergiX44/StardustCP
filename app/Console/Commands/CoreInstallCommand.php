<?php

namespace Core\Console\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Core\Service\MariaDbService;
use Core\Service\NodeJsService;
use Core\Service\PanelService;
use Core\Service\RoadRunnerService;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class CoreInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-install {--dev-mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or upgrade the application.';

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

        if ($this->option('dev-mode')) {
            $this->warn('WARNING! INSTALLATION STARTED IN "dev-mode", THIS IS ONLY FOR DEVELOPMENT PURPOSES!');
        }

        $env = OS::recognize();

        $result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?", true);
        if (!$result) {
            $this->error('Cannot detect you system.');
            return 1;
        }

        $hostname = trim(File::get('/etc/hostname'));
        $result = $this->confirm("The system hostname is '{$hostname}'. It's correct?", true);
        if (!$result) {
            $this->error('Wrong hostname, aborting.');
            return 1;
        }

        $rootPassword = $this->secret('Insert new DBMS password for "root". (Empty for generate): ');
        if ($rootPassword === null) {
            $rootPassword = str_random(16);
        }

        $pkg = $env->getPackageManager();
        $this->info("Your package manager is '{$pkg->name()}'.");

        $this->updateSystem($pkg);

        $this->installMariaDb($pkg, $rootPassword);
        config()->set('database.connections.root.password', $rootPassword);

        $this->info('Installing NodeJS...');
        NodeJsService::make()->install($pkg);

        $this->info('Configuring Panel...');
        PanelService::make()->configure([
            'hostname' => $hostname,
            'dev-mode' => $this->option('dev-mode')
        ]);

        $this->info('Generating app key...');
        Artisan::call('key:generate', ['--force' => true, '--no-interaction' => true]);

        $this->info('Migrating tables...');
        Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);

        if (!$this->option('dev-mode')) {
            $this->info('Optimizing app...');
            Artisan::call('optimize', ['--no-interaction' => true]);
        }

        $this->info('Configuring RoadRunner...');
        RoadRunnerService::make()->configure(['dev-mode' => $this->option('dev-mode')]);

        // Saving root password, and make it read-only for the root user
        File::put('.root_db', $rootPassword);
        File::chmod('.root_db', 0400);

        $this->warn("DBMS root password: {$rootPassword}");
        $this->info('Core installation completed!');

        if ($this->option('dev-mode')) {
            Artisan::call('db:seed', ['--force' => true, '--no-interaction' => true]);
        }

        return 0;
    }

    private function updateSystem(IPackageManager $pkg)
    {
        $this->info('Updating packages...');
        if (!$pkg->update() || !$pkg->upgrade()) {
            $this->error('Error during update.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
    }

    private function installMariaDb(IPackageManager $pkg, $password)
    {
        $this->info('Installing MariaDB...');
        $mariaDb = MariaDbService::make();
        if (!$mariaDb->install($pkg)) {
            $this->error('Error during MariaDB installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
        $this->info('Configuring MariaDB...');
        $mariaDb->configure(['password' => $password]);
    }
}
