<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'namespace' => 'Laravel\Passport\Http\Controllers',
], function (): void {
    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);

    Route::post('/token/refresh', [
        'uses' => 'TransientTokenController@refresh',
        'as' => 'token.refresh',
    ]);
});
