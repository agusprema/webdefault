<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Awcodes\Curator\Facades\Curator;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Curator::acceptedFileTypes(['video/mp4', 'video/x-m4v', 'video/*'])->maxSize('250000');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('use-translation-manager', function ($user) {
            return $user->can('view_QuickTranslate') ? true : null;
        });

        Filament::serving(function () {
            // Using Vite
            Filament::registerViteTheme('resources/css/app.css');
        });
    }
}
