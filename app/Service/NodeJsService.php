<?php


namespace Core\Service;


use Core\Environment\PackageManagers\IPackageManager;
use Symfony\Component\Process\Process;

class NodeJsService extends BaseService
{

    public function install(IPackageManager $pkg): bool
    {
        Process::fromShellCommandline('curl -sL https://deb.nodesource.com/setup_12.x | sudo bash -')->run();
        return $pkg->install(['nodejs', 'npm']);
    }

    public function remove(IPackageManager $pkg): bool
    {
        return $pkg->remove(['nodejs', 'npm']);
    }
}
