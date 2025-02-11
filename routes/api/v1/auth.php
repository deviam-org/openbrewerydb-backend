<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthController;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
    'as' => 'auth.',
], function (): void {

    Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');

});
