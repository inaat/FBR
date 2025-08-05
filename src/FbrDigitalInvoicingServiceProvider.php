<?php

namespace Fbr\DigitalInvoicing;

use Illuminate\Support\ServiceProvider;
use Fbr\DigitalInvoicing\Services\FbrService;
use Fbr\DigitalInvoicing\Services\FbrDigitalInvoicingService;
use Fbr\DigitalInvoicing\Services\FbrReferenceService;
use Fbr\DigitalInvoicing\Commands\FbrSyncCommand;

class FbrDigitalInvoicingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fbr-digital-invoicing.php', 'fbr-digital-invoicing');

        // Register individual services
        $this->app->singleton(FbrDigitalInvoicingService::class);
        $this->app->singleton(FbrReferenceService::class);

        // Register the main service
        $this->app->singleton('fbr-digital-invoicing', function ($app) {
            return new FbrService(
                $app->make(FbrDigitalInvoicingService::class),
                $app->make(FbrReferenceService::class)
            );
        });

        // Register commands
        $this->commands([
            FbrSyncCommand::class,
        ]);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/fbr-digital-invoicing.php' => config_path('fbr-digital-invoicing.php'),
            ], 'fbr-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'fbr-migrations');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
