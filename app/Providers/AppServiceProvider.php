<?php

namespace App\Providers;

use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.public', 'public.*'], function ($view): void {
            if (! $view->offsetExists('profile')) {
                try {
                    if (Schema::hasTable('school_profiles')) {
                        $view->with('profile', SchoolProfile::getActiveProfile());
                    }
                } catch (\Throwable) {
                    // Fail silently during initial setup/migration
                }
            }
        });
    }
}
