<?php

namespace OTIFSolutions\CurrencyLayer;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use OTIFSolutions\CurrencyLayer\Commands\FetchCurrencyRates;
use OTIFSolutions\CurrencyLayer\Commands\FillAllDatabaseTables;
use OTIFSolutions\CurrencyLayer\Commands\PublishResources;
use OTIFSolutions\CurrencyLayer\Commands\RemoveHistoricalRates;

class CurrencyLayerServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('rates:delete')->daily()->at('08:00');
        });

    }

    public function boot() {
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchCurrencyRates::class,
                RemoveHistoricalRates::class,
                FillAllDatabaseTables::class,
                PublishResources::class
            ]);

            $this->publishResources();
        }
    }

    public function publishResources() {
        $this->publishes([
            __DIR__ . '/public/flags' => public_path('flags/')
        ], 'otif-flags');
    }

}
