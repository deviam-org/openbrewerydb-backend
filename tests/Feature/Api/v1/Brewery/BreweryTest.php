<?php

declare(strict_types=1);

use App\Models\User;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use App\Http\Integrations\OpenBrewery\OpenBreweryConnector;
use App\Http\Integrations\OpenBrewery\Requests\ListBreweries;
use App\Http\Integrations\OpenBrewery\Requests\MetaDataBreweries;

beforeEach(function () {
    // Create a user
    $this->user = User::factory()->create();

    $mockClient = new MockClient();
    $this->connector = new OpenBreweryConnector;
    $this->connector->withMockClient($mockClient);

});

test('can list breweries with default parameters', function () {

    MockClient::global([
        ListBreweries::class => MockResponse::make(
            body: [
                [
                    "id" => "8f9621dc-da98-4ddb-82a1-039c9e2b3224",
                    "name" => "21st Amendment Brewery Cafe",
                    "brewery_type" => "brewpub",
                    "address_1" => "563 2nd St",
                    "address_2" => null,
                    "address_3" => null,
                    "city" => "San Francisco",
                    "state_province" => "California",
                    "postal_code" => "94107-1411",
                    "country" => "United States",
                    "longitude" => "-122.3925769",
                    "latitude" => "37.782448",
                    "phone" => "4153690900",
                    "website_url" => "http://www.21st-amendment.com",
                    "state" => "California",
                    "street" => "563 2nd St"
                ],
                [
                    "id" => "5ec3b488-48bd-49a7-9828-05bd90195cd5",
                    "name" => "2 Tread Brewing Co",
                    "brewery_type" => "brewpub",
                    "address_1" => "1018 Santa Rosa Plz",
                    "address_2" => null,
                    "address_3" => null,
                    "city" => "Santa Rosa",
                    "state_province" => "California",
                    "postal_code" => "95401-6399",
                    "country" => "United States",
                    "longitude" => "-122.7167729",
                    "latitude" => "38.4387767",
                    "phone" => "4152330857",
                    "website_url" => "http://www.2treadbrewing.com",
                    "state" => "California",
                    "street" => "1018 Santa Rosa Plz"
                ]

            ],
            status: 200,
        ),
        MetaDataBreweries::class => MockResponse::make(
            body: [
                "total" => "2",
                "page" => "1",
                "per_page" => "50"
            ],
            status: 200,
        ),
    ]);

    $this
        ->actingAs($this->user, 'api')
        ->withHeaders($this->headerUser)
        ->postJson(route('api.v1.breweries.index'))
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'brewery_type', 'address', 'phone', 'website_url', 'state', 'street']
            ],
            'meta' => ['total_items', 'total_pages', 'current_page', 'per_page']
        ])
        ->assertJson([
            'meta' => [
                'total_items' => 2,
                'total_pages' => 1,
                'current_page' => 1,
                'per_page' => 50
            ]
        ]);
});


test('handles external API errors gracefully', function () {
    MockClient::global([
        ListBreweries::class => MockResponse::make(
            body: ['message' => 'External API error'],
            status: 500
        ),
        MetaDataBreweries::class => MockResponse::make(
            body: ['message' => 'External API error'],
            status: 500
        )
    ]);

    $response = $this
        ->actingAs($this->user, 'api')
        ->withHeaders($this->headerUser)
        ->postJson(route('api.v1.breweries.index'));

    $response
        ->assertStatus(400)
        ->assertJson([
            'message' => 'Error loading data'
        ]);
});
