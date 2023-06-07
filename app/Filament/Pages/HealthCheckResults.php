<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\ResultStores\ResultStore;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class HealthCheckResults extends Page
{
    use HasPageShield;
    protected $listeners = ['refreshComponent' => '$refresh'];

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'filament-spatie-health::pages.health-check-results';

    protected function getActions(): array
    {
        return [
            Actions\Action::make(__('filament-spatie-health::health.pages.health_check_results.buttons.refresh'))->action('refresh'),
        ];
    }

    protected function getHeading(): string
    {
        return __('filament-spatie-health::health.pages.health_check_results.heading');
    }

    protected static function getNavigationGroup(): ?string
    {
        return config('filament.navigation_group.monitoring');
    }

    protected static function getNavigationLabel(): string
    {
        return __('filament-spatie-health::health.pages.health_check_results.navigation.label');
    }

    protected function getViewData(): array
    {
        $checkResults = app(ResultStore::class)->latestResults();

        return [
            'lastRanAt' => new Carbon($checkResults?->finishedAt),
            'checkResults' => $checkResults,
        ];
    }

    public function refresh(): void
    {
        Artisan::call(RunHealthChecksCommand::class);

        $this->emitSelf('refreshComponent');
    }
}
