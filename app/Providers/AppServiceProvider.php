<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Report;
use App\Models\Campaign;
use App\Policies\ReportPolicy;
use App\Policies\CampaignPolicy;

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
     * Mendaftarkan Policy untuk authorization (requirement UAS Web).
     */
    public function boot(): void
    {
        // Registrasi Policy: menghubungkan Model → Policy
        Gate::policy(Report::class, ReportPolicy::class);
        Gate::policy(Campaign::class, CampaignPolicy::class);
    }
}
