<?php

Route::get('/web', \Modules\Web\Controllers\TestController::class.'@test')->name('web.test');