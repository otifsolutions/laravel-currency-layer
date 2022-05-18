<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use OTIFSolutions\CurrencyLayer\Models\{City, State};
use Illuminate\Database\Seeder;
use JsonMachine\Exception\InvalidArgumentException;
use JsonMachine\Items;

class CitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function run() {
        ini_set('max_execution_time', 2500);
        $cities = Items::fromFile(__DIR__ . './../jsons/cities.json');

        foreach ($cities as $key => $city) {
            City::updateOrCreate([
                'name' => $city->name,
                'state_code' => $city->state_code
            ], [
                'name' => $city->name,
                'state_code' => $city->state_code,
                'latitude' => $city->latitude,
                'longitude' => $city->longitude,
                'wiki_data_id' => $city->wikiDataId,
                'state_id' => State::where('state_code', $city->state_code)->first()['id']
            ]);
        }
    }
}