<?php

namespace App\Listeners;

class RegisterCoreMenu
{
	/**
	 * Handle the event.
	 *
	 * @return void
	 */
    public function handle()
    {
	    app('menu')
		    ->section('Home', 0)
		    ->route('Dashboard', '<i class="fas fa-tachometer-alt"></i>', 'core.home')
		    ->section('System')
		    ->route('Configuration', '<i class="fas fa-cog"></i>', 'core.root');
    }
}
