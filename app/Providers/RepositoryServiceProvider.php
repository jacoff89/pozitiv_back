<?php

namespace App\Providers;

use App\Interfaces\TourRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;
use App\Repositories\TourRepository;
use App\Repositories\TripRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TripRepositoryInterface::class, TripRepository::class);
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
