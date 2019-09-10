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
		Blade::include('layouts.form.input', 'form_input');
		Blade::include('layouts.form.submit', 'form_submit');
		Blade::include('layouts.form.radiobtn', 'form_radio');
		Blade::include('layouts.form.select', 'form_select');
		Blade::include('layouts.form.switch', 'form_switch');
	}
}
