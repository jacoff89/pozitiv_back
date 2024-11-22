<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Tour;
use App\Models\Tourist;
use App\Models\Trip;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TouristPolicy;
use App\Policies\TourPolicy;
use App\Policies\TripPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Review::class => ReviewPolicy::class,
        Tour::class => TourPolicy::class,
        Trip::class => TripPolicy::class,
        Tourist::class => TouristPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
