<?php

namespace OTIFSolutions\CurrencyLayer\Database\Seeders;

use Illuminate\Database\Seeder;
use JsonMachine\Items;
use OTIFSolutions\CurrencyLayer\Models\{Country, State};

class StateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \JsonMachine\Exception\InvalidArgumentException
     */
    public function run() {
        $states = Items::fromFile(__DIR__ . './../jsons/states.json');
        foreach ($states as $state) {

            State::updateOrCreate([
                'name' => $state->name,
                'state_code' => $state->state_code
            ],
                [
                    'name' => $state->name,
                    'state_code' => $state->state_code,
                    'country_id' => Country::where('iso2', $state->country_code)->first()['id'],
                    'latitude' => $state->latitude,
                    'longitude' => $state->longitude
                ]);
        }
    }
}
