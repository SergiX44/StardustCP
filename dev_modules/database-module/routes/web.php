<?php

use Modules\Database\Controllers\TestController;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'database', 'as' => 'database.'], function () {
	Route::get('/test', [TestController::class, 'test'])->name('test');
	Route::get('/configure', [TestController::class, 'configure'])->name('configure');
});