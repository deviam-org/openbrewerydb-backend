<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;


test('user can login with valid credentials', function () {
    // Create a test user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123')
    ]);

    // Attempt login
    $this->json(
        'POST',
        route('api.v1.auth.login'),
        [
            'email' => 'test@example.com',
            'password' => 'password123'
        ],
        $this->headerUser
    )
    ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'access_token',
                'refresh_token',
                'expires_in',
                'user' => ['id', 'name', 'email']
            ],
            'message'
        ])
    ->assertJson(['message' => 'Login successful']);

});

test('login fails with invalid credentials', function () {
    // Create a test user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123')
    ]);

    // Attempt login
    $this->json(
        'POST',
        route('api.v1.auth.login'),
        [
            'email' => 'test@example.com',
            'password' => 'wrong_password'
        ],
        $this->headerUser
    )
    ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('login fails with non-existent email', function () {
    // Attempt login
    $this->json(
        'POST',
        route('api.v1.auth.login'),
        [
            'email' => 'nonexistent@example.com',
            'password' => 'wrong_password'
        ],
        $this->headerUser
    )
    ->assertStatus(422)
    ->assertJsonValidationErrors(['email']);
});

test('login requires email and password', function () {
    // Attempt login without credentials
    $this->json(
        'POST',
        route('api.v1.auth.login'),
        [
            'email' => 'nonexistent@example.com',
            'password' => 'wrong_password'
        ],
        $this->headerUser
    )
    ->assertStatus(422)
    ->assertJsonValidationErrors(['email']);
});
