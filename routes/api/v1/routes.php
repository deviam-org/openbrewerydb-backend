<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/ping', static fn () => ['ping' => 'pong'])->name('ping');

require __DIR__.'/auth.php';
require __DIR__.'/breweries.php';
require __DIR__.'/passport.php';
