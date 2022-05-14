<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {

        $this->call([
            CountrySeeder::class,
            TimezoneSeeder::class,
            StateSeeder::class,
            CurrencySeeder::class,
            CitySeeder::class
        ]);

    }

}
