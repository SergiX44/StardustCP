<?php

namespace Core\Providers;

use Core\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register the module manager
		$this->app->singleton(ModuleManager::class, function () {
			return new ModuleManager();
		});
		$this->app->alias(ModuleManager::class, 'modules');
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
