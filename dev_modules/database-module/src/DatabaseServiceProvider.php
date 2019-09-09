<?php

namespace Modules\Database;

use Core\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{

	/**
	 * Register the application services.
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'database-module');

		$this->app->register(DatabaseEventServiceProvider::class);
	}

	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		app(ModuleManager::class)->register(DatabaseModule::class);

		$this->loadMigrationsFrom(__DIR__ . '/../migrations/');
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/../views', 'database');

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('database-module.php'),
			], 'database-module');
		}
	}
}
