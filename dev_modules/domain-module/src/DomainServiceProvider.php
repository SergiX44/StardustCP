<?php

namespace Modules\Domain;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		app('modules')->register(DomainModule::class);

		$this->loadMigrationsFrom(__DIR__ . '/../migrations/');
	}
}
