<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [];
        if (($open = fopen(__DIR__ . './../csvs/states.csv', "r + b")) !== FALSE) {
            while (($student = fgetcsv($open, NULL, ",")) !== FALSE) {
                $data[] = $student;
            }
            fclose($open);
        }

        $numCsvRecords = count($data);
        for ($i = 1; $i < $numCsvRecords; $i++) {
            State::updateOrCreate([
                'name' => $data[$i][1],
                'country_code' => $data[$i][3],
                'country_name' => $data[$i][4],
                'state_code' => $data[$i][5],
                'latitude' => $data[$i][7],
                'longitude' => $data[$i][8],
            ]);
        }
    }
}
