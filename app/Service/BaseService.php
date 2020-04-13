<?php


namespace Core\Service;


use Core\Environment\PackageManagers\IPackageManager;

abstract class BaseService
{
    public static function make()
    {
        return new static();
    }

    public function install(IPackageManager $pkg): bool
    {
        return true;
    }

    public function configure(array $config = []): void { }

    public function update(IPackageManager $pkg): bool
    {
        return true;
    }

    public function remove(IPackageManager $pkg): bool
    {
        return true;
    }
}
