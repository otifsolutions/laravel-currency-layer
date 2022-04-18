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
        $filename = __DIR__ . './../csvs/cities.csv';
        $rows = $this->csvRowsCount($filename);
        $itemsPerRun = 50000;
        for ($i = 0; $i <= $rows; $i += $itemsPerRun + 1) {
            $cities = array();
            $chunk = $this->sliceCsv($filename, $i, $itemsPerRun);
            foreach ($chunk as $item) {
                $cities[] = [
                    'name' => $item->name,
                    'state_code' => $item->state_code,
                    'state_name' => $item->state_name,
                    'country_code' => $item->country_code,
                    'country_name' => $item->country_name,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'wiki_data_id' => $item->wikiDataId
                ];
            }

            foreach (array_chunk($cities, 5000) as $part) {
                City::upsert(
                    $part, ['wiki_data_id']
                );
            }

        }

    }

    private function sliceCsv($filename, $start, $desiredCount) {
        $row = 0;
        $count = 0;
        $rows = array();
        if (($handle = fopen($filename, 'rb')) === FALSE) {
            return FALSE;
        }

        while (($singleRowData = fgetcsv($handle, 2000, ",")) !== FALSE) {
            // Grab headings.
            if ($row === 0) {
                $headings = $singleRowData;
                $row++;
                continue;
            }

            // Not there yet.
            if ($row++ < $start) {
                continue;
            }

            $rows[] = (object)array_combine($headings, $singleRowData);
            $count++;
            if ($count === $desiredCount) {
                return $rows;
            }
        }
        return $rows;
    }

    private function csvRowsCount($filename) {
        ini_set('auto_detect_line_endings', TRUE);
        $rowsCounter = 0;
        if (($handle = fopen($filename, 'rb')) !== FALSE) {
            while (($rowData = fgetcsv($handle, 2000, ",")) !== FALSE) {
                $rowsCounter++;
            }
            fclose($handle);
            // Exclude the headings.
            $rowsCounter--;
            return $rowsCounter;
        }
    }

}
