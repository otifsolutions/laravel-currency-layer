<?php

namespace OTIFSolutions\CurrencyLayer;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use OTIFSolutions\CurrencyLayer\Commands\FetchCurrencyRates;
use OTIFSolutions\CurrencyLayer\Commands\RemoveHistoricalRates;

class CurrencyLayerServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('rates:delete')->daily()->at('08:00');
        });

        include __DIR__ . '/Database/Seeders/CountrySeeder.php';
        include __DIR__ . '/Database/Seeders/TimezoneSeeder.php';
        include __DIR__ . '/Database/Seeders/StateSeeder.php';
        include __DIR__ . '/Database/Seeders/CitySeeder.php';
        include __DIR__ . '/Database/Seeders/CurrencySeeder.php';


        $this->publishes([
            __DIR__ . '/public' => public_path('flags/'),
        ], 'otifsolutions-flags');

    }

    public function boot() {
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchCurrencyRates::class,
                RemoveHistoricalRates::class
            ]);
        }
    }

}
