<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        ini_set('max_execution_time', 1000);
        $data = [];
        if (($open = fopen(__DIR__ . './../csvs/countries.csv', 'r + b')) !== FALSE) {
            while (($timezone = fgetcsv($open, NULL, ',')) !== FALSE) {
                $data[] = $timezone;
            }
            fclose($open);
        }

        $data = $this->replaceChars($data, ['[', ']']);
        $data = $this->replaceChars($data, (array)'\/', ' - ');
        $dataNumRecords = count($data);

        $resultingArray = [];
        $subArray = [];

        for ($i = 1; $i < $dataNumRecords; $i++) {
            $resultingArray[$data[$i][1]] = ($this->fixJson($data[$i][14]));
        }

        $jsonData = [];
        foreach ($resultingArray as $singleString) {
            if (is_null(json_decode($singleString))) {
                $singleString .= '}';
            }
            $jsonData[] = json_decode($singleString);
        }

        foreach ($resultingArray as $key => $jsonData) {

            if (str_contains($resultingArray[$key], '},')) {  // this line means that certain country does have multiple timezones
                $subArray[] = $this->fixJsonString(explode('},', $resultingArray[$key]));
                unset($resultingArray[$key]);
            } else {
                $subArray[] = $jsonData;
            }

            $countryObj = Country::where('name', $key)->first();

            $ids = [];
            foreach ($subArray as $timezone) {
                if (is_string($timezone)) {     // if string, then  there is single timezone
                    $ids[] = $this->insertTimezone($timezone);
                } else {    // if array, there are mulitple timeoznes
                    $timezones = $this->fixJsonString($timezone);
                    foreach ($timezones as $singleTimezone) {
                        $ids[] = $this->insertTimezone($singleTimezone);
                    }
                }
            }
            $countryObj->timezones()->sync($ids);
        }
    }

    private function insertTimezone(string $timezone) {
        $timezone = json_decode($timezone);
        $timezoneObj = Timezone::updateOrCreate(['zone_name' => $timezone->zoneName], [
            'zone_name' => $timezone->zoneName,
            'gmt_offset' => $timezone->gmtOffset,
            'gmt_offset_name' => $timezone->gmtOffsetName,
            'abbreviation' => $timezone->abbreviation,
            'tz_name' => $timezone->tzName,
        ]);
        return $timezoneObj['id'];
    }

    private function replaceChars($hayStack, array $charsArray, $character = ''): array {
        $tempArray = [];
        foreach ($hayStack as $item) {
            $tempArray[] = str_replace($charsArray, $character, $item);
        }
        return $tempArray;
    }



    private function fixJson(string $str): string {
        return preg_replace(
            '/(?<=(\{|\,))(\w+)(?=\:)/',
            '"$2"',
            str_replace("'", '"', $str)
        );
    }

    private function fixJsonString(array $stringsArray): array {
        foreach ($stringsArray as $iValue) {
            if (!str_contains($iValue, '}')) {
                $stringsArray[] = $iValue . '}';
            }
        }
        return $stringsArray;
    }

}
