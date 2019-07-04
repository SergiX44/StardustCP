<?php

namespace App\Providers;

use App\Menu\Menu;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(Menu::class, function ($app) {
			return Menu::make('sidebar-menu', 'menu-header');
		});
		$this->app->alias(Menu::class, 'menu');
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app->booted(function () {
			app('menu')
				->section('Home', 0)
				->route('Dashboard', '<i class="fas fa-tachometer-alt"></i>', 'home')
				->section('System')
				->route('Configuration', '<i class="fas fa-cog"></i>', 'root');
		});
	}
}
