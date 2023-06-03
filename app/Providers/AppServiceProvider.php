<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use io3x1\FilamentSitemap\Pages\SiteSettings as BaseSiteSettings;
use App\Filament\Pages\SiteSettings;
use Illuminate\Support\Facades\Gate;
use Awcodes\Curator\Facades\Curator;

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
        $this->app->bind(BaseSiteSettings::class, SiteSettings::class);
        Gate::define('use-translation-manager', function ($user) {
            return $user->can('view_QuickTranslate') ? true : null;
        });
    }
}
