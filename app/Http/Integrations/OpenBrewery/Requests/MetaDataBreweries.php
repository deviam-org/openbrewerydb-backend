<?php

declare(strict_types=1);

namespace App\Http\Integrations\OpenBrewery\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

final class MetaDataBreweries extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $byCity = null,
        protected ?string $byName = null,
        protected ?string $byCountry = null,
        protected ?string $byState = null,
        protected ?string $byPostal = null,
        protected ?string $byType = null,
        protected int $page = 1,
        protected int $perPage = 10,
    ) {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $queryParams = [];

        if ($this->byCity) {
            $queryParams['by_city'] = $this->byCity;
        }
        if ($this->byName) {
            $queryParams['by_name'] = $this->byName;
        }
        if ($this->byCountry) {
            $queryParams['by_country'] = $this->byCountry;
        }
        if ($this->byState) {
            $queryParams['by_state'] = $this->byState;
        }
        if ($this->byPostal) {
            $queryParams['by_postal'] = $this->byPostal;
        }
        if ($this->byType) {
            $queryParams['by_type'] = $this->byType;
        }
        if ($this->page) {
            $queryParams['page'] = $this->page;
        }
        if ($this->perPage) {
            $queryParams['per_page'] = $this->perPage;
        }

        $queryString = $queryParams ? '?'.http_build_query($queryParams) : '';
        return '/breweries/meta'.$queryString;
    }

    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return [
            'total_items' => $data['total'],
            'total_pages' => (int) ceil($data['total'] / $data['per_page']),
            'current_page' => $data['page'],
            'per_page' => $data['per_page']
        ];
    }
}
