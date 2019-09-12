<?php

namespace Core\Console\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
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

		$result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?", true);
		if (!$result) {
			$this->error('Cannot detect you system.');
			exit(1);
		}

		$pkg = $env->getPackageManager();
		$this->info("Your package manager is '{$pkg->name()}'.");

		$hostname = trim(file_get_contents('/etc/hostname'));

		$result = $this->confirm("The system hostname is '{$hostname}'. It's correct?", true);
		if (!$result) {
			$this->error('Wrong hostname, aborting.');
			exit(1);
		}

		$this->updateSystem($pkg);
		$this->installDBMS($pkg);
		$dbmsPassword = $this->configureDBMS();
		$this->configurePanel($hostname, $dbmsPassword);

		Artisan::call('key:generate');
		Artisan::call('migrate', ['--force' => '']);
		Artisan::call('optimize');

		$this->warn("MySQL root password: {$dbmsPassword}");

		$this->info('Core installation completed!');
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

	/**
	 * @return array
	 * @throws \Safe\Exceptions\FilesystemException
	 */
	private function configureDBMS() {
		$password = $this->secret('Insert new MariaDB password for "root". (Empty for generate): ');
		if ($password === null) {
			$password = str_random(16);
		}

		// secure install and set root password
		$this->info('Securing installation...');
		$proc = Process::fromShellCommandline('mysql_secure_installation');
		$proc->setPty(true);
		$proc->setInput("\ny\n{$password}\n{$password}\ny\ny\ny\ny\n");
		$proc->run();

		$this->info('Configuring services...');

		$myDotConf = '/etc/mysql/mariadb.conf.d/50-server.cnf';
		if (!file_exists($myDotConf)) {
			$myDotConf = '/etc/mysql/my.cnf';
		}

		$content = file_get_contents($myDotConf);

		umask(0644);

		// bind mysql on all interfaces
		preg_replace('/^(bind-address)/m', '#bind-address', $content, 1);

		file_put_contents($myDotConf, $content);

		// password authentication method in MariaDB to native
		Process::fromShellCommandline("mysql -uroot -p{$password} -e \"update mysql.user set plugin = 'mysql_native_password' where user='root';\"")->run();

		$this->info('Configure open file limit...');

		//security file
		$openFileLimit = "\nmysql soft nofile 65535\nmysql hard nofile 65535";
		file_put_contents('/etc/security/limits.conf', $openFileLimit, FILE_APPEND);

		// systemd config
		$openFileLimit = "[Service]\nLimitNOFILE=infinity";
		if (!file_exists('/etc/systemd/system/mysql.service.d/')) {
			mkdir('/etc/systemd/system/mysql.service.d/', 0755, true);
		}
		file_put_contents('/etc/systemd/system/mysql.service.d/limits.conf', $openFileLimit);

		$this->info('Restart service...');
		Process::fromShellCommandline('systemctl daemon-reload && systemctl restart mysql')->run();

		$this->warn('Done.');

		return $password;
	}

	/**
	 * @param $hostname
	 * @param $dbmsPassword
	 * @throws \Safe\Exceptions\FilesystemException
	 */
	private function configurePanel($hostname, $dbmsPassword) {

		// create database
		$stardustPassword = str_random(16);

		Process::fromShellCommandline("mysql -uroot -p{$dbmsPassword} -e \"CREATE DATABASE stardust /*\!40100 DEFAULT CHARACTER SET utf8 */;\"")->run();
		Process::fromShellCommandline("mysql -uroot -p{$dbmsPassword} -e \"CREATE USER stardust@localhost IDENTIFIED BY '{$stardustPassword}';\"")->run();
		Process::fromShellCommandline("mysql -uroot -p{$dbmsPassword} -e \"GRANT ALL PRIVILEGES ON stardust.* TO 'stardust'@'localhost';\"")->run();
		Process::fromShellCommandline("mysql -uroot -p{$dbmsPassword} -e \"FLUSH PRIVILEGES;\"")->run();

		$dotEnv = <<< ENV
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://{$hostname}:8443

LOG_CHANNEL=daily
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=stardust
DB_USERNAME=stardust
DB_PASSWORD="{$stardustPassword}"

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
ENV;
		file_put_contents('.env', $dotEnv);

	}
}
