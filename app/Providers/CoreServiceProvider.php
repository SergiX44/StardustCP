<?php

namespace Core\Providers;


use Core\Modules\ModuleManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
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
		$this->setupBladeAliases();
	}

	/**
	 * Register blade aliases
	 */
	private function setupBladeAliases()
	{
		Blade::include('layouts.form.input', 'formInput');
		Blade::include('layouts.form.submit', 'formSubmit');
		Blade::include('layouts.form.radiobtn', 'formRadio');
		Blade::include('layouts.form.select', 'formSelect');
		Blade::include('layouts.form.switch', 'formSwitch');
		Blade::include('layouts.form.textarea', 'formTextarea');
	}
}
