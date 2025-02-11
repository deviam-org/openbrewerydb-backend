<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Resources\Api\v1\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this['access_token'],
            'refresh_token' => $this['refresh_token'],
            'expires_in' => $this['expires_in'],
            'user' => UserResource::make($this['user'])
        ];
    }
}
