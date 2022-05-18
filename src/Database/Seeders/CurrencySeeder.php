<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use OTIFSolutions\CurrencyLayer\Models\{Country, Currency};
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $data = [];
        if (($open = fopen(__DIR__ . './../csvs/countries.csv', "r + b")) !== FALSE) {
            while (($singleRecord = fgetcsv($open, NULL, ",")) !== FALSE) {
                $data[] = $singleRecord;
            }
            fclose($open);
        }

        $totalRecords = count($data);
        for ($i = 1; $i < $totalRecords; $i++) {
            $insertArray[$i] = [
                'currency' => $data[$i][7], // AFN
                'name' => $data[$i][8],     //  afghan afghani
                'symbol' => $data[$i][9],   // ؋
                'country_id' => Country::where('iso2', $data[$i][3])->first()['id'],
            ];
        }

        Currency::upsert($insertArray, [
            'country_id'
        ]);

    }
}
