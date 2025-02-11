<?php

declare(strict_types=1);

namespace App\Http\Integrations\OpenBrewery\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use App\Data\Brewery\BreweryDtoData;

final class ListBreweries extends Request implements HasBody
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
        protected ?string $sort = null,
        protected ?string $sortDirection = null
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
        if (1 !== $this->page) {
            $queryParams['page'] = $this->page;
        }
        if (10 !== $this->perPage) {
            $queryParams['per_page'] = $this->perPage;
        }
        if ($this->sort) {
            $queryParams['sort'] = $this->sort;
        }

        if ($this->sortDirection && $this->sort) {
            $queryParams['sort'] = $queryParams['sort'].':'.$this->sortDirection;
        }

        $queryString = $queryParams ? '?'.http_build_query($queryParams) : '';
        return '/breweries'.$queryString;
    }

    public function createDtoFromResponse(Response $response): array
    {
        return BreweryDtoData::collect($response->json());
    }
}
