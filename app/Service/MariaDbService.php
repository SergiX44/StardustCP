<?php


namespace Core\Service;


use Core\Environment\PackageManagers\IPackageManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class MariaDbService extends BaseService
{
    public function install(IPackageManager $pkg): bool
    {
        return $pkg->install(['mariadb-server', 'mariadb-client']);
    }

    public function configure(array $config = []): void
    {
        $password = $config['password'] ?? null;

        if ($password === null) {
            throw new \InvalidArgumentException('The password must be set.');
        }

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
        File::put("/etc/mysql/mariadb.conf.d/55-{$slug}-overrides.cnf", View::make('templates.mariadb.overrides'));
        File::chmod("/etc/mysql/mariadb.conf.d/55-{$slug}-overrides.cnf", 0644);

        // security file
        File::append('/etc/security/limits.conf', View::make('templates.mariadb.limits'));

        // systemd config
        if (!File::isDirectory('/etc/systemd/system/mysql.service.d/')) {
            File::makeDirectory('/etc/systemd/system/mysql.service.d/', 0755, true);
        }
        File::put('/etc/systemd/system/mysql.service.d/limits.conf', View::make('templates.mariadb.systemd_limits'));
        File::chmod('/etc/systemd/system/mysql.service.d/limits.conf', 0644);

        Process::fromShellCommandline('systemctl daemon-reload && systemctl restart mysql')->run();
    }

    public function remove(IPackageManager $pkg): bool
    {
        return $pkg->remove(['mariadb-server', 'mariadb-client']);
    }
}
