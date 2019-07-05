<?php

namespace Modules\Web;

use Illuminate\Support\ServiceProvider;

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
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		$this->loadViewsFrom(__DIR__ . '/../views', 'web');

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('web-module.php'),
			], 'web-module');
		}
	}
}
