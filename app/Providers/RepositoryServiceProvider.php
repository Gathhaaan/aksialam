<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\CampaignRepositoryInterface::class,
            \App\Repositories\CampaignRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
