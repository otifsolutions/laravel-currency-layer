<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $data = [];

        if (($open = fopen(__DIR__ . '/countries.csv', 'r + b')) !== FALSE) {
            while (($student = fgetcsv($open, NULL, ',')) !== FALSE) {
                $data[] = $student;
            }
            fclose($open);
        }

        $recordCounter = count($data);
        for ($i = 1; $i < $recordCounter; $i++) {
            Currency::updateOrCreate([
                'currency' => $data[$i][7],
                'currency_name' => $data[$i][8],
                'currency_symbol' => $data[$i][9],
            ]);
        }

    }
}
