<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;
    use RefreshDatabase;

    public array $headerUser = [];

    public ?User $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
        $this->headerUser = $this->setHeaders();
    }

    protected function setHeaders(): array
    {
        $data = [];
        $data['Accept'] = 'application/json';
        $data['Content'] = 'application/json';
        $data['X-Api-Key'] = config('app.api_key');

        return $data;
    }
}
