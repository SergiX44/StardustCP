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

class InstallerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-install';

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
     * @return void
     * @throws \Safe\Exceptions\FilesystemException
     */
    public function handle()
    {

        if (posix_getuid() !== 0) {
            $this->line('You must run the installer as root.');
            exit(1);
        }

        $env = OS::recognize();

        $result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?", true);
        if (!$result) {
            $this->error('Cannot detect you system.');
            exit(1);
        }

        $hostname = trim(File::get('/etc/hostname'));
        $result = $this->confirm("The system hostname is '{$hostname}'. It's correct?", true);
        if (!$result) {
            $this->error('Wrong hostname, aborting.');
            exit(1);
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
        File::put('.root_db', $rootPassword);
        File::chmod('.root_db', 0400);

        $this->configurePanel($hostname);

        $this->info('Generating app key...');
        Artisan::call('key:generate', ['--force' => true]);
        $this->warn('Done.');

        $this->info('Migrating tables...');
        Artisan::call('migrate', ['--force' => true]);
        $this->warn('Done.');

        Artisan::call('optimize');

        $this->warn("DBMS root password: {$rootPassword}");
        $this->info('Core installation completed!');
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

        $appName = config('app.name');
        File::put("/etc/mysql/mariadb.conf.d/100-{$appName}-overrides.cnf", View::make('templates.mariadb.overrides'));
        File::chmod("/etc/mysql/mariadb.conf.d/100-{$appName}-overrides.cnf", 0644);

        $this->info('Configure open file limit...');

        //security file
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
        $stardustPassword = str_random(16);

        $rootConn = DB::reconnect('root');
        $rootConn->statement('CREATE DATABASE IF NOT EXISTS stardust CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        $rootConn->statement("CREATE USER IF NOT EXISTS 'stardust'@'localhost' IDENTIFIED BY ".$rootConn->getPdo()->quote($stardustPassword));
        $rootConn->statement("GRANT ALL PRIVILEGES ON stardust.* TO 'stardust'@'localhost'");
        $rootConn->statement('FLUSH PRIVILEGES');
        DB::disconnect();

        File::put('.env', View::make('templates.core.env', [
            'hostname' => $hostname,
            'password' => $stardustPassword,
            'connectionName' => 'app',
        ]));

        config()->set('database.connections.app.password', $stardustPassword);

        $this->warn('Done.');
    }
}
