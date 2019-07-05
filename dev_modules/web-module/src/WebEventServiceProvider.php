<?php


namespace Modules\Web;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Web\Listeners\RegisterWebMenu;

class WebEventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the module.
	 *
	 * @var array
	 */
	protected $listen = [
		'menu.ready' => [
			RegisterWebMenu::class,
		],
	];
}