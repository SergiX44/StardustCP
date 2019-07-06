<?php

use Modules\Web\Controllers\TestController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'web', 'as' => 'web.'], function () {
	Route::get('/test', [TestController::class, 'test'])->name('test');
	Route::get('/configure', [TestController::class, 'configure'])->name('configure');
});