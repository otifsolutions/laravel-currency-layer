<?php

namespace OtifSolutions\CurrencyLayer\CurrencyLayerServiceProvider;

use App\Console\Commands\FetchCurrencyRates;
use App\Console\Commands\RemoveHistoricalRates;
use Illuminate\Support\ServiceProvider;

class CurrencyLayerServiceProvider extends ServiceProvider {

    public function register(): void {

        include __DIR__ . '/../src/database/seeders/CountrySeeder.php';
        include __DIR__ . '/../src/database/seeders/TimezoneSeeder.php';
        include __DIR__ . '/../src/database/seeders/StateSeeder.php';
        include __DIR__ . '/../src/database/seeders/CitySeeder.php';
        include __DIR__ . '/../src/database/seeders/CurrencySeeder.php';

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/laravel-currency-layer'),
        ], 'public');

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

}
