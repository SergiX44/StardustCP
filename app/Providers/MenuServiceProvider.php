<?php

namespace App\Providers;

use App\Menu\AppMenu;
use Illuminate\Support\ServiceProvider;
use Spatie\Menu\Laravel\Menu;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->singleton(AppMenu::class, function ($app) {
		    //
	    });
	    $this->app->alias(AppMenu::class, 'menu');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
	    $this->app->booted(function () {
//		    app('menu')
//			    ->section('System')
//			    ->routeWithIcon('home', 'Dashboard', '<i class="fas fa-fire"></i>');
	    });
    }
}
