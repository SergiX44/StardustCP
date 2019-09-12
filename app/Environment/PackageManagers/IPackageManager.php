<?php


namespace Core\Environment\PackageManagers;


use Core\Environment\OS;

interface IPackageManager {

	/**
	 * IPackageManager constructor.
	 * @param OS $os
	 */
	public function __construct(OS $os);

	/**
	 * Name of the package manager
	 * @return string
	 */
	public function name(): string;

	/**
	 * @param string $package
	 * @return bool
	 */
	public function isInstalled(string $package): bool;

	/**
	 * @param mixed $package
	 * @return bool
	 */
	public function install($package): ?bool;

	/**
	 * @return bool
	 */
	public function upgrade(): bool;

	/**
	 * @return bool
	 */
	public function update(): bool;

	/**
	 * @param mixed $package
	 * @param bool $purge
	 * @return bool
	 */
	public function remove($package, bool $purge = false): bool;

	/**
	 * @return mixed
	 */
	public function getLastStdOut();

	/**
	 * @return mixed
	 */
	public function getLastStdErr();

}