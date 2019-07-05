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
		    ->route('Test', '<i class="fas fa-check"></i>', 'web.test');
    }
}
