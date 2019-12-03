<?php

namespace Modules\Web;

use Core\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;
use Modules\Web\Commands\InstallCommand;

class WebServiceProvider extends ServiceProvider
{

	/**
	 * Register the application services.
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'web-module');

		$this->app->register(WebEventServiceProvider::class);
	}

	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		app(ModuleManager::class)->register(WebModule::class);

		$this->loadMigrationsFrom(__DIR__ . '/../migrations/');
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/../views', 'web');

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('web-module.php'),
			], 'web-module');

            $this->commands([
                InstallCommand::class
            ]);
		}
	}
}
