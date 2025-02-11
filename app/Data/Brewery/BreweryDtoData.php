<?php

declare(strict_types=1);

namespace App\Data\Brewery;

use Spatie\LaravelData\Data;

class BreweryDtoData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $brewery_type,
        public ?string $address_1,
        public ?string $address_2,
        public ?string $address_3,
        public string $city,
        public ?string $state_province,
        public ?string $postal_code,
        public ?string $country,
        public ?string $longitude,
        public ?string $latitude,
        public ?string $phone,
        public ?string $website_url,
        public ?string $state,
        public ?string $street
    ) {
    }

}
