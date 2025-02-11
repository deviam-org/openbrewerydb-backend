<?php

declare(strict_types=1);

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
        Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::enablePasswordGrant();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->configureUrl();

        JsonResource::withoutWrapping();
    }

    public function configureModels(): void
    {
        Model::shouldBeStrict(
            shouldBeStrict: ! $this->app->environment('production'),
        );

        Model::preventLazyLoading(
            value: ! app()->isProduction()
        );

        if ( ! $this->app->environment('production')) {

            Model::handleLazyLoadingViolationUsing(function ($model, $relation): void {
                $class = get_class($model);
                info("Attempted to lazy load [{$relation}] on model [{$class}].");
            });

            DB::listen(function ($query): void {
                if ($query->time > 500) {
                    Log::warning("An individual database query exceeded 500ms", [
                        'sql' => $query->sql
                    ]);
                }
            });
        }
    }

    public function configureUrl(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
