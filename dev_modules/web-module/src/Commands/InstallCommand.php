<?php

namespace Modules\Web\Commands;

use Core\Environment\OS;
use Core\Environment\PackageManagers\IPackageManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or upgrade the web module.';

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
     * @return int
     * @throws \Safe\Exceptions\FilesystemException
     */
    public function handle()
    {
        if (posix_getuid() !== 0) {
            $this->line('You must run the installer as root.');
            return 1;
        }

        $env = OS::recognize();

        $result = $this->confirm("Detected {$env->getPrettyName()}. It's correct?", true);
        if (!$result) {
            $this->error('Cannot detect you system.');
            return 1;
        }

        $pkg = $env->getPackageManager();
        $pkg->update();

        $this->installWebServer($pkg);
        $this->configureWebServer($pkg);

        $this->createDefaultVirtualHost();

        return 0;
    }

    protected function installWebServer(?IPackageManager $pkg)
    {
        $this->info('Installing Apache and packages...');
        if (!$pkg->install(['apache2', 'apache2-doc', 'apache2-utils', 'apache2-suexec-pristine', 'ssl-cert'])) {
            $this->error('Error during Apache installation.');
            $this->error($pkg->getLastStdOut());
            exit(1);
        }
        $this->warn('Done.');
    }

    protected function configureWebServer(?IPackageManager $pkg)
    {
        $this->info('Configuring Apache...');
        Process::fromShellCommandline('a2enmod suexec rewrite ssl actions include dav_fs dav auth_digest cgi headers actions proxy_fcgi alias http2')->run();

        File::put('/etc/apache2/conf-available/httpoxy.conf', View::make('web::templates.apache.httpoxy'));

        Process::fromShellCommandline('a2enconf httpoxy')->run();
        Process::fromShellCommandline('systemctl restart apache2')->run();

        $this->warn('Done.');
    }

    protected function createDefaultVirtualHost()
    {
        File::deleteDirectory('/var/www/html');
        File::makeDirectory('/var/www/default');
        File::put('/var/www/default/index.html', "You shouldn't be here. Get out.");

        File::put('/etc/apache2/sites-available/000-default.conf', View::make('web::templates.apache.default'));
        Process::fromShellCommandline('systemctl restart apache2')->run();
    }
}
