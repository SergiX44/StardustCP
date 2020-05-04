<?php

namespace Core\Providers;

use Core\Menu\Menu;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Menu::class, function () {
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
            event('menu.ready');
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [Menu::class];
    }
}
