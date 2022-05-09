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

//        $this->publishes([
//            __DIR__ . '/../database/seeders/CitySeeder.php' => database_path('seeders/CitySeeder.php'),
//        ], 'otifsolutions-seeder');

        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }


//        include __DIR__ . '/../src/database/seeders/CountrySeeder.php';
//        include __DIR__ . '/../src/database/seeders/TimezoneSeeder.php';
//        include __DIR__ . '/../src/database/seeders/StateSeeder.php';
//        include __DIR__ . '/../src/database/seeders/CitySeeder.php';
//        include __DIR__ . '/../src/database/seeders/CurrencySeeder.php';

        $this->publishes([
            __DIR__ . '/../public' => public_path('flags/'),
        ], 'otifsolutions-flags');

    }

    public function boot() {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchCurrencyRates::class,
                RemoveHistoricalRates::class
            ]);
        }
    }

    private function publishResources() {
        $this->publishes([
            __DIR__ . '/../database/seeders/CitySeeder.php' => database_path('seeders/CitySeeder.php'),
        ], 'otifsolutions-seeder');
        $this->publishes([
            __DIR__ . '/../database/seeders/CountrySeeder.php' => database_path('seeders/CountrySeeder.php'),
        ], 'otifsolutions-seeder');
        $this->publishes([
            __DIR__ . '/../database/seeders/CurrencySeeder.php' => database_path('seeders/CurrencySeeder.php'),
        ], 'otifsolutions-seeder');
        $this->publishes([
            __DIR__ . '/../database/seeders/StateSeeder.php' => database_path('seeders/StateSeeder.php'),
        ], 'otifsolutions-seeder');
        $this->publishes([
            __DIR__ . '/../database/seeders/TimezoneSeeder.php' => database_path('seeders/TimezoneSeeder.php'),
        ], 'otifsolutions-seeder');

    }


}
