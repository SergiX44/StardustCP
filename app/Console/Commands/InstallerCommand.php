<?php

namespace Core\Console\Commands;

use Core\AuraX\StringBuilder;
use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;
use function Safe\file_get_contents;
use Symfony\Component\Process\Process;

class InstallerCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'self-install';

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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 * @throws \Safe\Exceptions\FilesystemException
	 */
	public function handle() {

		if (posix_getuid() !== 0) {
			$this->line('You must run the installer as root.');
			exit(1);
		}

		$env = OS::recognize();

		$result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?");
		if (!$result) {
			$this->error('Cannot detect you system.');
			exit(1);
		}

		$pkg = $env->getPackageManager();
		$this->info("Your package manager is '{$pkg->name()}'.");

		$hostname = trim(file_get_contents('/etc/hostname'));

		$result = $this->confirm("The system hostname is '{$hostname}'. It's correct?");
		if (!$result) {
			$this->error('Wrong hostname, aborting.');
			exit(1);
		}

		$this->updateSystem($pkg);
		$this->installDBMS($pkg);
		$dbmsPassword = $this->configureDBMS();
		$this->writeDotEnv($hostname, $dbmsPassword);


		Artisan::call('key:generate');
		Artisan::call('migrate', ['--force']);
		Artisan::call('optimize');

		$this->info('Installation completed!');
	}

	private function updateSystem(IPackageManager $pkg) {

		$this->info('Updating packages...');
		if (!$pkg->update() || !$pkg->upgrade()) {
			$this->error('Error during update.');
			$this->error($pkg->getLastStdOut());
			exit(1);
		}
		$this->warn('Done.');
	}

	private function installDBMS(IPackageManager $pkg) {
		$this->info('Installing MariaDB...');
		if (!$pkg->install(['mariadb-server', 'mariadb-client'])) {
			$this->error('Error during MariaDB installation.');
			$this->error($pkg->getLastStdOut());
			exit(1);
		}
		$this->warn('Done.');
	}

	private function configureDBMS() {
		$password = $this->secret('Insert new MariaDB password for "root". (Empty for generate): ');
		if ($password === null) {
			$password = str_random(16);
		}

		$this->info('Configuring MariaDB...');
		$proc = Process::fromShellCommandline('mysql_secure_installation', null, null,);
		$proc->setPty(true);
		$proc->setInput("\ny\n{$password}\n{$password}\ny\ny\ny\ny\n");
		$proc->run();
		$this->warn('Done.');

		// TODO: complete mariasb config, add stardust db

		return $password;
	}

	private function writeDotEnv($hostname, $dbmsPassword) {
		$dotEnv = new StringBuilder();
		$dotEnv->appendLine('APP_ENV=production');
		$dotEnv->appendLine('APP_KEY=');
		$dotEnv->appendLine('APP_DEBUG=false');
		$dotEnv->appendLineFormat('APP_URL=http://{0}:8443', $hostname);
		$dotEnv->appendLine('LOG_CHANNEL=daily');
		$dotEnv->appendLine('DB_CONNECTION=mysql');
		$dotEnv->appendLine('DB_HOST=localhost');
		$dotEnv->appendLine('DB_PORT=3306');
		$dotEnv->appendLine('DB_DATABASE=stardustcp');
		$dotEnv->appendLine('DB_USERNAME=stardust');
		$dotEnv->appendLine('DB_USERNAME=');

		// TODO: complete and write .env config

	}
}
