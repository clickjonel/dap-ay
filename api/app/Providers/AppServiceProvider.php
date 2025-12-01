<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Team;
use App\Observers\TeamObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Team::observe(TeamObserver::class);
    }
}
