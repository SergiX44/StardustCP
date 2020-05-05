<?php


namespace Core\Service;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;

class RoadRunnerService extends BaseService
{
    public function configure(array $config = []): void
    {
        $devMode = $config['dev-mode'] ?? null;
        if ($devMode === null) {
            throw new \InvalidArgumentException('dev-mode flag must be set.');
        }

        Process::fromShellCommandline(base_path('vendor/bin/rr').' get', base_path())->run();
        $slug = str_slug(config('app.name'));

        $baseDir = base_path();
        Process::fromShellCommandline("useradd --shell /bin/false --user-group --home-dir {$baseDir} {$slug}")->run();

        File::put("/etc/systemd/system/{$slug}-web.service", View::make('templates.roadrunner.rr-service', [
            'user' => $slug,
            'group' => $slug,
            'workingPath' => $baseDir,
            'rrPath' => base_path('rr')
        ]));

        if ($devMode) {
            Process::fromShellCommandline("usermod -aG vboxsf {$slug}")->run();
            Process::fromShellCommandline("usermod -aG root {$slug}")->run();
        } else {
            Process::fromShellCommandline("chown -R {$slug} ".base_path(), null, null, null, null)->run();
        }

        Process::fromShellCommandline("systemctl daemon-reload")->run();
        Process::fromShellCommandline("systemctl enable {$slug}.service")->run();
        Process::fromShellCommandline("systemctl restart {$slug}.service")->run();
    }
}
