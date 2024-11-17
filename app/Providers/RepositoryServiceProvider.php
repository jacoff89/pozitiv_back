<?php

namespace App\Providers;

use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;
use App\Interfaces\ReviewRepositoryInterface;
use App\Interfaces\TourRepositoryInterface;
use App\Interfaces\TouristRepositoryInterface;
use App\Interfaces\TripRepositoryInterface;
use App\Repositories\AdditionalServiceRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\TourRepository;
use App\Repositories\TouristRepository;
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
        $this->app->bind(TouristRepositoryInterface::class, TouristRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(AdditionalServiceRepositoryInterface::class, AdditionalServiceRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
