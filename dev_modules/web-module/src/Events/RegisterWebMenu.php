<?php

namespace Modules\Web\Listeners;

class RegisterWebMenu
{
	/**
	 * Handle the event.
	 *
	 * @return void
	 */
    public function handle()
    {
	    app('menu')
		    ->section('Web', 1)
		    ->route('Websites', '<i class="fas fa-globe"></i>', 'web.test')
		    ->route('FTP accounts', '<i class="fas fa-user-cog"></i>', 'web.test')
		    ->route('SSH users', '<i class="fas fa-terminal"></i>', 'web.test')
		    ->route('Cron jobs', '<i class="fas fa-clock"></i>', 'web.test')
		    ->section('Statistics', -1)
		    ->route('Web statistics', '<i class="fas fa-chart-line"></i>', 'web.test');
    }
}
