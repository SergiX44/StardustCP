<?php

namespace Modules\Database\Listeners;

class RegisterDatabaseMenu
{
	/**
	 * Handle the event.
	 *
	 * @return void
	 */
    public function handle()
    {
	    app('menu')
		    ->section('Database', 2)
	        ->route('Databases', '<i class="fas fa-database"></i>', 'database.test')
	        ->route('Databases users', '<i class="fas fa-user-astronaut"></i>', 'database.test')
		    ->section('Statistics', -1)
		    ->route('Database statistics', '<i class="fas fa-chart-area"></i>', 'database.test');
    }
}
