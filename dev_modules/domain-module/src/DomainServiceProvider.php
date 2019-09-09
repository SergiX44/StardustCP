<?php

namespace Modules\Domain;

use Core\Modules\ModuleManager;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		app(ModuleManager::class)->register(DomainModule::class);

		$this->loadMigrationsFrom(__DIR__ . '/../migrations/');
	}
}
