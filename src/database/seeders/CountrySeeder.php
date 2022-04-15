<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder {

    public function run() {

        $countriesData = [];

        if (($open = fopen(__DIR__ . './../csvFiles/countries.csv', "r + b")) !== FALSE) {
            while (($singleRecord = fgetcsv($open, NULL, ",")) !== FALSE) {
                $countriesData[] = $singleRecord;
            }

            fclose($open);
        }

        $countriesData = array_slice($countriesData, '1');  // slice the first row, colomnNames

        foreach ($countriesData as $singleRecord) {
            Country::updateOrCreate([
                'name' => $singleRecord[1],
                'iso3' => $singleRecord[2],
                'iso2' => $singleRecord[3],
                'numeric_code' => $singleRecord[4],
                'phone_code' => $singleRecord[5],
                'capital' => $singleRecord[6],
                'tld' => $singleRecord[10],
                'native' => $singleRecord[11],
                'region' => $singleRecord[12],
                'subregion' => $singleRecord[13],
                'latitude' => $singleRecord[15],
                'longitude' => $singleRecord[16],
                'flag' => strtolower($singleRecord[3]) . '.png',
            ]);
        }

    }

}
