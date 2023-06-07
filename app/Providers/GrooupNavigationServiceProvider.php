<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource;

class GrooupNavigationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        QueueMonitorResource::navigationGroup(config('filament.navigation_group.monitoring'));
    }
}
