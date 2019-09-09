<?php

use Modules\Web\Controllers\TestController;
use Modules\Web\Controllers\WebsitesController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'web', 'as' => 'web.'], function () {
	Route::resource('websites', WebsitesController::class);
	Route::get('test', [TestController::class, 'test'])->name('test');
	Route::get('configure', [TestController::class, 'configure'])->name('configure');
});