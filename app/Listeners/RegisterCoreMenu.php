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
		    ->route('Dashboard', '<i class="fas fa-tachometer-alt"></i>', 'home')
		    ->section('System')
		    ->route('Configuration', '<i class="fas fa-cog"></i>', 'root');
    }
}
