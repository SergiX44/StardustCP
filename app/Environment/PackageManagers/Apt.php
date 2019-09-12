<?php


namespace Core\Environment\PackageManagers;


use Core\Environment\OS;
use Symfony\Component\Process\Process;

class Apt implements IPackageManager {

	protected $lastStdOut;
	protected $lastStdErr;

	private $env = ['DEBIAN_FRONTEND' => 'noninteractive'];

	/**
	 * Apt constructor.
	 * @param OS $os
	 */
	public function __construct(OS $os) { }

	/**
	 * Name of the package manager
	 * @return string
	 */
	public function name(): string {
		return 'APT';
	}

	/**
	 * @param string $package
	 * @return bool
	 */
	public function isInstalled(string $package): bool {
		$apt = new Process(['apt', '-qq', 'list', $package], null, $this->env);
		$apt->run();

		$this->lastStdOut = $apt->getOutput();
		$this->lastStdErr = $apt->getErrorOutput();

		preg_match('/\s\[(.*)\]/m', $this->lastStdOut, $match);
		return isset($match[1]);
	}

	/**
	 * @param mixed $packages
	 * @return bool
	 */
	public function install($packages): bool {
		if (!is_array($packages)) {
			$packages = [$packages];
		}

		$apt = new Process(array_merge(['apt', 'install', '-y'], $packages), null, $this->env);
		$apt->run();

		$this->lastStdOut = $apt->getOutput();
		$this->lastStdErr = $apt->getErrorOutput();

		return $apt->isSuccessful();
	}

	/**
	 * @return bool
	 */
	public function upgrade(): bool {
		$apt = new Process(array_merge(['apt', 'upgrade', '-y']), null, $this->env);
		$apt->run();

		$this->lastStdOut = $apt->getOutput();
		$this->lastStdErr = $apt->getErrorOutput();

		return $apt->isSuccessful();
	}

	/**
	 * @return bool
	 */
	public function update(): bool {
		$apt = new Process(array_merge(['apt', 'update']), null, $this->env);
		$apt->run();

		$this->lastStdOut = $apt->getOutput();
		$this->lastStdErr = $apt->getErrorOutput();

		return $apt->isSuccessful();
	}

	/**
	 * @param mixed $packages
	 * @param bool $purge
	 * @return bool
	 */
	public function remove($packages, bool $purge = false): bool {
		if (!is_array($packages)) {
			$packages = [$packages];
		}

		$mode = 'remove';
		if ($purge) {
			$mode = 'purge';
		}

		$apt = new Process(array_merge(['apt', $mode, '-y'], $packages), null, $this->env);
		$apt->run();

		$this->lastStdOut = $apt->getOutput();
		$this->lastStdErr = $apt->getErrorOutput();

		return $apt->isSuccessful();
	}

	/**
	 * @return mixed
	 */
	public function getLastStdOut() {
		return $this->lastStdOut;
	}

	/**
	 * @return mixed
	 */
	public function getLastStdErr() {
		return $this->lastStdErr;
	}
}