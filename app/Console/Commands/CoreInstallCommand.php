<?php

namespace Core\Console\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
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
        $this->installDBMS($pkg);
        $this->configureDBMS($rootPassword);
        config()->set('database.connections.root.password', $rootPassword);

        $this->configurePanel($hostname);

        $this->info('Generating app key...');
        Artisan::call('key:generate', ['--force' => true, '--no-interaction' => true]);
        $this->warn('Done.');

        $this->info('Migrating tables...');
        Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
        $this->warn('Done.');

        if (!$this->option('dev-mode')) {
            $this->info('Optimizing app...');
            Artisan::call('optimize', ['--no-interaction' => true]);
            $this->warn('Done.');
        }

        $this->installNode($pkg);
        $this->compileAssets();

        $this->configureRoadRunner();

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
        $this->warn('Done.');
    }

    private function installDBMS(IPackageManager $pkg)
    {
        $this->info('Installing MariaDB...');
        if (!$pkg->install(['mariadb-server', 'mariadb-client'])) {
            $this->error('Error during MariaDB installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
        $this->warn('Done.');
    }

    /**
     * @param $password
     * @return void
     */
    private function configureDBMS($password)
    {
        $this->info('Configuring DBMS...');

        // set native password plugin
        Process::fromShellCommandline("mysql -uroot -p{$password} -e \"update mysql.user set plugin = 'mysql_native_password' where user='root';\"")->run();
        Process::fromShellCommandline('systemctl restart mysql')->run();

        $rootConn = DB::reconnect('root');

        // set root password
        $rootConn->statement("UPDATE mysql.user SET password=PASSWORD(?) WHERE user='root'", [$password]);

        // remove anonymous users
        $rootConn->statement("DELETE FROM mysql.user WHERE user=''");

        // disallow root remote login
        $rootConn->statement("DELETE FROM mysql.user WHERE user='root' AND host NOT IN ('localhost', '127.0.0.1', '::1')");

        // drop tests databases
        $rootConn->statement("DROP DATABASE IF EXISTS test");
        $rootConn->statement("DELETE FROM mysql.db WHERE db='test' OR db='test\\_%'");

        // reload perms
        $rootConn->statement("FLUSH PRIVILEGES");

        DB::disconnect();

        $slug = str_slug(config('app.name'));
        File::put("/etc/mysql/mariadb.conf.d/100-{$slug}-overrides.cnf", View::make('templates.mariadb.overrides'));
        File::chmod("/etc/mysql/mariadb.conf.d/100-{$slug}-overrides.cnf", 0644);

        $this->info('Configure open file limit...');

        // security file
        File::append('/etc/security/limits.conf', View::make('templates.mariadb.limits'));

        // systemd config
        if (!File::isDirectory('/etc/systemd/system/mysql.service.d/')) {
            File::makeDirectory('/etc/systemd/system/mysql.service.d/', 0755, true);
        }
        File::put('/etc/systemd/system/mysql.service.d/limits.conf', View::make('templates.mariadb.systemd_limits'));
        File::chmod('/etc/systemd/system/mysql.service.d/limits.conf', 0644);

        $this->info('Restart services...');
        Process::fromShellCommandline('systemctl daemon-reload && systemctl restart mysql')->run();

        $this->warn('Done.');
    }

    /**
     * @param $hostname
     */
    private function configurePanel($hostname)
    {
        // create database
        $this->info('Configuring control panel...');
        $password = str_random(16);
        $slug = str_slug(config('app.name'));

        $rootConn = DB::reconnect('root');
        $rootConn->statement("CREATE DATABASE IF NOT EXISTS $slug CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $rootConn->statement("CREATE USER IF NOT EXISTS '$slug'@'localhost' IDENTIFIED BY ".$rootConn->getPdo()->quote($password));
        $rootConn->statement("GRANT ALL PRIVILEGES ON $slug.* TO '$slug'@'localhost'");
        $rootConn->statement('FLUSH PRIVILEGES');
        DB::disconnect();

        $params = [
            'db' => $slug,
            'username' => $slug,
            'hostname' => $hostname,
            'password' => $password,
            'connectionName' => 'app',
            'port' => 8443
        ];

        if ($this->option('dev-mode')) {
            $params['env'] = 'local';
            $params['debug'] = 'true';
        }

        File::put('.env', View::make('templates.core.env', $params));

        config()->set('database.connections.app.username', $slug);
        config()->set('database.connections.app.password', $password);
        config()->set('database.connections.app.database', $slug);
        DB::reconnect('app');

        $this->warn('Done.');
    }

    private function configureRoadRunner()
    {
        $this->info('Configuring application server...');
        Process::fromShellCommandline(base_path('vendor/bin/rr').' get', base_path())->run();
        $slug = str_slug(config('app.name'));

        $baseDir = base_path();
        Process::fromShellCommandline("useradd -d {$baseDir} {$slug}")->run();

        if ($this->option('dev-mode')) {
            Process::fromShellCommandline("usermod -aG vboxsf {$slug}")->run();
            Process::fromShellCommandline("usermod -aG root {$slug}")->run();
        }

        File::put("/etc/systemd/system/$slug.service", View::make('templates.roadrunner.rr-service', [
            'user' => $slug,
            'group' => $slug,
            'workingPath' => $baseDir,
            'rrPath' => base_path('rr')
        ]));

        if (!$this->option('dev-mode')) {
            Process::fromShellCommandline("chown -R {$slug} ".base_path(), null, null, null, null)->run();
        }

        Process::fromShellCommandline("systemctl daemon-reload")->run();
        Process::fromShellCommandline("systemctl enable {$slug}")->run();
        Process::fromShellCommandline("systemctl restart {$slug}")->run();

        $this->warn('Done.');
    }

    private function installNode($pkg)
    {
        $this->info('Installing NodeJS...');
        Process::fromShellCommandline('curl -sL https://deb.nodesource.com/setup_12.x | sudo bash -')->run();
        if (!$pkg->install(['nodejs', 'npm'])) {
            $this->error('Error during nodeJS installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
        $this->warn('Done.');
    }

    private function compileAssets()
    {
        $this->info('Compiling CSS/JS assets...');
        Process::fromShellCommandline('npm -g install yarn', null, null, null, null)->run();
        Process::fromShellCommandline('yarn install', base_path(), null, null, null)->run();
        Process::fromShellCommandline('yarn run '.($this->option('dev-mode') ? 'development' : 'production'), base_path(), null, null, null)->run();
        $this->warn('Done.');
    }
}
