<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\BreweryController;

Route::prefix('breweries')
    ->name('breweries.')
    ->middleware('auth:api')
    ->group(
        function (): void {
            Route::post('/index', [BreweryController::class, 'index'])->name('index');
        }
    );
