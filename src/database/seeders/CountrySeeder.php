<?php

namespace OTIFSolutions\CurrencyLayer\Seeders;

use OTIFSolutions\CurrencyLayer\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder {

    public function run() {
        $countries = json_decode(file_get_contents(__DIR__ . './../jsons/countries.json'), true, 512, JSON_THROW_ON_ERROR);

        foreach ($countries as $country) {
            Country::updateOrCreate([
                'name' => $country['name'], // Afghanistan
                'iso3' => $country['iso3'], // AFG
                'iso2' => $country['iso2'], // af
                'numeric_code' => $country['numeric_code'], // 004
                'phone_code' => $country['phone_code'], // +93
                'capital' => $country['capital'],   // Kabul
                'tld' => $country['tld'],   //  .af
                'native' => $country['native'], // افغانستان
                'region' => $country['region'], // Asia
                'subregion' => $country['subregion'],   // southern Asia
                'latitude' => $country['latitude'],
                'longitude' => $country['longitude'],
                'flag' => $country['flag']
            ]);
        }
    }

}
