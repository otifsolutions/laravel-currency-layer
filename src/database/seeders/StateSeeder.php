<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use OTIFSolutions\CurrencyLayer\Models\{Country, State};
use Illuminate\Database\Seeder;
use JsonMachine\Items;

class StateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \JsonMachine\Exception\InvalidArgumentException
     */
    public function run() {
        $states = Items::fromFile(__DIR__ . './../jsons/states.json');
        $data = [];
        foreach ($states as $state) {
            $data[] = [
                'name' => $state->name,
                'country_code' => $state->country_code,
                'state_code' => $state->state_code,
                'country_id' => Country::where('iso2', $state->country_code)->first()['id'],
                'latitude' => $state->latitude,
                'longitude' => $state->longitude
            ];

        }

        State::upsert($data, [
            'name', 'country_code'
        ], ['name', 'country_code', 'state_code', 'country_id', 'latitude', 'longitude']);

    }

}
