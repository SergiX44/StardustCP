<?php


namespace Core\Environment;

use Core\Environment\PackageManagers\Apt;
use Core\Environment\PackageManagers\IPackageManager;
use function Safe\file_get_contents;

class OS {
	const DEBIAN = 'debian';

	const OS_RELEASE_PATH = '/etc/os-release';

	/** @var string */
	protected $prettyName;

	/** @var string */
	protected $distro;

	/** @var int */
	protected $version;

	/** @var string */
	protected $codename;

	/**
	 * OS constructor.
	 * @param string $prettyName
	 * @param string $distro
	 * @param int $version
	 * @param string $codename
	 */
	private function __construct(string $prettyName, string $distro, int $version, string $codename) {
		$this->prettyName = $prettyName;
		$this->distro = $distro;
		$this->version = $version;
		$this->codename = $codename;
	}


	/**
	 * @throws \Safe\Exceptions\FilesystemException
	 */
	public static function recognize() {
		$releaseFile = file_get_contents(self::OS_RELEASE_PATH);

		preg_match('/^PRETTY_NAME=\"(.*)\"/m', $releaseFile, $match);
		$prettyName = $match[1];

		preg_match('/^ID=(.*)/m', $releaseFile, $match);
		$distro = strtolower($match[1]);

		preg_match('/^VERSION_ID=\"(.*)\"/m', $releaseFile, $match);
		$version = (int)$match[1];

		preg_match('/^VERSION_CODENAME=(.*)/m', $releaseFile, $match);
		$codename = strtolower($match[1]);

		return new static($prettyName, $distro, $version, $codename);
	}

	/**
	 * @param $distro
	 * @param null $version
	 * @return bool
	 */
	public function is($distro, $version = null): bool {
		if ($version === null) {
			return $distro === $this->distro;
		}
		return $distro === $this->distro && $version === $this->version;
	}

	/**
	 * @return IPackageManager|null
	 */
	public function getPackageManager(): ?IPackageManager {
		if ($this->is(self::DEBIAN)) {
			return new Apt($this);
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getPrettyName(): string {
		return $this->prettyName;
	}

	/**
	 * @return string
	 */
	public function getDistro(): string {
		return $this->distro;
	}

	/**
	 * @return int
	 */
	public function getVersion(): int {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function getCodename(): string {
		return $this->codename;
	}
}