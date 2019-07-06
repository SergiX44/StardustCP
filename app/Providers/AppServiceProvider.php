<?php

namespace App\Providers;

use App\Modules\ModulesManager;
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
		$this->app->singleton(ModulesManager::class, function () {
			return new ModulesManager();
		});
		$this->app->alias(ModulesManager::class, 'modules');
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
