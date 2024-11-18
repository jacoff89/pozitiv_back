<?php

namespace App\Providers;

use App\Models\Review;
use App\Models\Tour;
use App\Policies\ReviewPolicy;
use App\Policies\TourPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Review::class => ReviewPolicy::class,
        Tour::class => TourPolicy::class,
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
