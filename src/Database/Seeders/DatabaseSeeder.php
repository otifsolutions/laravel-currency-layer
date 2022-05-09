<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use OTIFSolutions\CurrencyLayer\Models\{City, Country, Currency, State, Timezone};

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's Database.
     *
     * @return void
     */
    public function run() {

        $this->call([
            Country::class,
            Timezone::class,
            State::class,
            Currency::class,
            City::class
        ]);

    }

}
