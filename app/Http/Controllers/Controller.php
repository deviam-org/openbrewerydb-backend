<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Saloon\Http\Response;
use App\Traits\ApiResponse;

abstract class Controller
{
    use ApiResponse;

    public function handleFailedRequest(Response $response)
    {
        return match ($response->status()) {
            401 => $this->respondUnAuthenticated(),
            403 => $this->respondForbidden(),
            404 => $this->respondNotFound(),
            422 => $this->respondFailedValidation(
                $response->json()['message'],
                $response->json()['errors']
            ),
            500 => $this->respondError(
                'Internal server error'
            ),
            default => $this->respondError(
                'Internal server error'
            ),
        };
    }
}
