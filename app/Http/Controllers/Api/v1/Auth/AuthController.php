<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;

final class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ( ! $user || ! Hash::check($request->password, $user->password)) {
            return $this->respondFailedValidation(
                message: 'Invalid email or password',
                errors: [
                    'email' => 'Invalid email or password',
                ]
            );
        }

        $passwordGrantClient = Client::where('password_client', 1)->first();

        if ( ! $passwordGrantClient) {
            return $this->respondError('No password grant client found');
        }

        $data = [
            'grant_type' => 'password',
            'username' => $request->email,
            'password' => $request->password,
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'scope' => '',
        ];

        $tokenRequest = Request::create(
            '/api/v1/oauth/token',
            'POST',
            $data,
            [],
            [],
            ['HTTP_X-Api-Key' => config('app.api_key')]
        );

        try {

            $response = app()->handle($tokenRequest);
            $content = json_decode($response->getContent(), true);

            if (isset($content['error'])) {
                return $this->respondError($content['error_description'] ?? 'Authentication failed');
            }

            $content['user'] = $user;

            return $this->respondSuccess(
                data: AuthResource::make($content),
                metaData: [],
                message: 'Login successful'
            );

        } catch (Exception $e) {
            return $this->respondError('Token generation failed: '.$e->getMessage());
        }
    }
}
