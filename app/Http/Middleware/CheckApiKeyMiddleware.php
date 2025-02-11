<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckApiKeyMiddleware
{
    use ApiResponse;

    public const AUTH_HEADER = 'X-Api-Key';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header(self::AUTH_HEADER) === config('app.api_key')) {
            return $next($request);
        }

        return $this->respondForbidden(
            'No Api Key provided',
        );
    }
}
