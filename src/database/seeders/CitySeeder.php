<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        set_time_limit(30);
        $filename = __DIR__ . '/cities.csv';
        $rows = $this->_csv_row_count($filename);
        $items_per_run = 50000;
        for ($i = 0; $i <= $rows; $i += $items_per_run + 1) {
            $cities = array();
            $chunk = $this->_csv_slice($filename, $i, $items_per_run);
            foreach ($chunk as $item) {
                $cities[] = [
                    'name' => $item->name,
                    'state_code' => $item->state_code,
                    'state_name' => $item->state_name,
                    'country_code' => $item->country_code,
                    'country_name' => $item->country_name,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'wikiDataId' => $item->wikiDataId
                ];

            }
            foreach (array_chunk($cities, 5000) as $part) {
                City::upsert(
                    $part, ['wikiDataId']
                );
            }

        }

    }

    private function _csv_slice($filename, $start, $desired_count) {
        $row = 0;
        $count = 0;
        $rows = array();
        if (($handle = fopen($filename, "r")) === FALSE) {
            return FALSE;
        }
        while (($row_data = fgetcsv($handle, 2000, ",")) !== FALSE) {
            // Grab headings.
            if ($row == 0) {
                $headings = $row_data;
                $row++;
                continue;
            }

            // Not there yet.
            if ($row++ < $start) {
                continue;
            }

            $rows[] = (object)array_combine($headings, $row_data);
            $count++;
            if ($count == $desired_count) {
                return $rows;
            }
        }
        return $rows;
    }

    private function _csv_row_count($filename) {
        ini_set('auto_detect_line_endings', TRUE);
        $row_count = 0;
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($row_data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                $row_count++;
            }
            fclose($handle);
            // Exclude the headings.
            $row_count--;
            return $row_count;
        }
    }

}
