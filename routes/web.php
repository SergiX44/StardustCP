<?php
use Core\Http\Controllers\ConfigurationController;
use Core\Http\Controllers\HomeController;
use Core\Http\Controllers\IPController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace('Core\Http\Controllers')->group(function() {
    Auth::routes(['register' => false]);
});

Route::group(['middleware' => ['auth'], 'as' => 'core.'], function () {
    Route::get('/', [HomeController::class, 'root'])->name('root');
    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'configuration'], function () {
        Route::get('/', [ConfigurationController::class, 'configuration'])->name('configuration');
        Route::resource('ip', IPController::class);
    });
});

