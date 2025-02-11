<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1\Brewery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BreweryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brewery_type' => $this->brewery_type,
            'address' => [
                'address_1' => $this->address_1,
                'address_2' => $this->address_2,
                'address_3' => $this->address_3,
                'city' => $this->city,
                'state_province' => $this->state_province,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
            ],
            'phone' => $this->phone,
            'website_url' => $this->website_url,
            'state' => $this->state,
            'street' => $this->street
        ];
    }
}
