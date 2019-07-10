<?php


namespace Modules\Database;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Database\Listeners\RegisterDatabaseMenu;

class DatabaseEventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the module.
	 *
	 * @var array
	 */
	protected $listen = [
		'menu.ready' => [
			RegisterDatabaseMenu::class,
		],
	];
}