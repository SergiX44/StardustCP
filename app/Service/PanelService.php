<?php


namespace Core\Service;


use Core\Models\IP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class PanelService extends BaseService
{
    public function configure(array $config = []): void
    {
        $hostname = $config['hostname'] ?? null;
        $devMode = $config['dev-mode'] ?? null;

        if ($hostname === null || $devMode === null) {
            throw new \InvalidArgumentException('The hostname and dev-mode must be set.');
        }

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

        if ($devMode) {
            $params['env'] = 'local';
            $params['debug'] = 'true';
        }

        File::put('.env', View::make('templates.core.env', $params));

        config()->set('database.connections.app.username', $slug);
        config()->set('database.connections.app.password', $password);
        config()->set('database.connections.app.database', $slug);
        DB::reconnect('app');

        Process::fromShellCommandline('npm -g install yarn', null, null, null, null)->run();
        Process::fromShellCommandline('yarn install', base_path(), null, null, null)->run();
        Process::fromShellCommandline('yarn run '.($devMode ? 'development' : 'production'), base_path(), null, null, null)->run();

        $this->addSystemIps();
    }

    private function addSystemIps()
    {
        $cmd = Process::fromShellCommandline('hostname --all-ip-addresses');
        $cmd->run();

        $ips = explode(' ', $cmd->getOutput());

        foreach ($ips as $ip) {
            $ipModel = new IP();
            $ipModel->address = $ip;
            $ipModel->type = isIpv6($ip) ? 'ipv6' : 'ipv4';
            $ipModel->save();
        }
    }
}
