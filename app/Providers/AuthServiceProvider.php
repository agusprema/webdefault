<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \BezhanSalleh\FilamentExceptions\Models\Exception::class => \App\Policies\ExceptionPolicy::class,
        \HusamTariq\FilamentDatabaseSchedule\Models\Schedule::class => \App\Policies\SchedulePolicy::class,
        \Spatie\Activitylog\Models\Activity::class => \App\Policies\ActivityPolicy::class,
        \Spatie\TranslationLoader\LanguageLine::class => \App\Policies\LanguageLinePolicy::class,
        \Croustibat\FilamentJobsMonitor\Models\QueueMonitor::class => \App\Policies\QueueMonitorPolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
        \Spatie\Permission\Models\Permission::class => \App\Policies\PermissionPolicy::class,
        \ShuvroRoy\FilamentSpatieLaravelBackup\Models\BackupDestination::class => \App\Policies\BackupsPolicy::class,
        \IbrahimBedir\FilamentDynamicSettingsPage\Models\Setting::class => \App\Policies\SettingPagePolicy::class,
        \Awcodes\Curator\Models\Media::class => \App\Policies\MediaPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
