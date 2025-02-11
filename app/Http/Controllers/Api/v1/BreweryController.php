<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\FatalRequestException;
use App\Http\Resources\Api\v1\Brewery\BreweryResource;
use App\Http\Requests\Api\v1\Brewery\IndexBreweryRequest;
use App\Http\Integrations\OpenBrewery\OpenBreweryConnector;
use App\Http\Integrations\OpenBrewery\Requests\ListBreweries;
use App\Http\Integrations\OpenBrewery\Requests\MetaDataBreweries;

class BreweryController extends Controller
{
    public function __construct(protected OpenBreweryConnector $openBreweryConnector)
    {
    }

    public function index(
        IndexBreweryRequest $request
    ) {

        $cacheKey = $this->generateCacheKey($request);
        $cacheDuration = now()->addHours(24);

        return Cache::remember(
            $cacheKey,
            $cacheDuration,
            function () use ($request) {

                $requestData = new ListBreweries(
                    byCity: $request->byCity,
                    byName: $request->byName,
                    byCountry: $request->byCountry,
                    byState: $request->byState,
                    byPostal: $request->byPostal,
                    byType: $request->byType,
                    page: $request->page ?? 1,
                    perPage: $request->perPage ?? 10,
                    sort: $request->sort,
                    sortDirection: $request->sortDirection,
                );

                $requestMetaData = new MetaDataBreweries(
                    byCity: $request->byCity,
                    byName: $request->byName,
                    byCountry: $request->byCountry,
                    byState: $request->byState,
                    byPostal: $request->byPostal,
                    byType: $request->byType,
                );

                try {
                    $responseData = $this->openBreweryConnector->send($requestData);
                    $responseMetaData = $this->openBreweryConnector->send($requestMetaData);

                    if ($responseData->failed()) {
                        return $this->handleFailedRequest($responseData);
                    }

                    if ($responseMetaData->failed()) {
                        return $this->handleFailedRequest($responseMetaData);
                    }

                    $responseDataDto = $responseData->dtoOrFail();
                    $responseMetaDataDto = $responseMetaData->dtoOrFail();

                    return $this->respondSuccess(
                        data: BreweryResource::collection($responseDataDto),
                        metaData: $responseMetaDataDto,
                        message: 'Data loaded successfully',
                    );
                } catch (FatalRequestException | RequestException) {
                    return $this->respondError(
                        message: 'Error loading data',
                    );
                }
            }
        );
    }

    /**
     * Create a unique identifier based on all request parameters
     *
     * @param  IndexBreweryRequest  $request
     * @return string
     */
    private function generateCacheKey(IndexBreweryRequest $request): string
    {
        $cacheKeyParts = [
            'breweries',
            $request->byCity ?? 'no_city',
            $request->byName ?? 'no_name',
            $request->byCountry ?? 'no_country',
            $request->byState ?? 'no_state',
            $request->byPostal ?? 'no_postal',
            $request->byType ?? 'no_type',
            'page_'.($request->page ?? 1),
            'per_page_'.($request->perPage ?? 10),
            $request->sort ?? 'default_sort',
            $request->sortDirection ?? 'default_sort_direction',
        ];

        return 'brewery_api_'.Str::slug(implode('_', $cacheKeyParts));
    }
}
