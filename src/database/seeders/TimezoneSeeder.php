<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [];
        if (($open = fopen(__DIR__ . '/countries.csv', 'r + b')) !== FALSE) {
            while (($singleRecord = fgetcsv($open, NULL, ',')) !== FALSE) {
                $data[] = $singleRecord;
            }
            fclose($open);
        }

        $data = $this->removeCharacters($data, ['[', ']']);
        $data = $this->removeCharacters($data, (array)'\/', ' - ');
        $dataNumRecords = count($data);

        $resultingArray = [];
        $subArray = [];
        for ($i = 1; $i < $dataNumRecords; $i++) {
            $resultingArray[$i] = ($this->fixJson($data[$i][14]));
            if (str_contains($resultingArray[$i], '},')) {
                $subArray[] = $this->fixJsonString(explode('},', $resultingArray[$i]));
                unset($resultingArray[$i]);
            }
        }

        foreach ($subArray as $value) {
            foreach ($value as $singleArray) {
                $resultingArray[] = $singleArray;
            }
        }

        $index221 = json_decode($resultingArray[221]);
        $index231 = json_decode($resultingArray[231]);

        $jsonData = [];
        foreach ($resultingArray as $singleString) {
            if (is_null(json_decode($singleString))) {
                $singleString .= '}';
            }
            $jsonData[] = json_decode($singleString);
        }

        // these two records were producing NULL, we enforcelly set decodeJson to the indexes
        $jsonData[221] = $index221;
        $jsonData[231] = $index231;

        foreach ($jsonData as $singleRecord) {
            $singleRecord = (array)($singleRecord);     // converting all StdClass objects to array
            Timezone::create([
                'zone_name' => $singleRecord['zoneName'],
                'gmt_offset' => $singleRecord['gmtOffset'],
                'gmt_offset_name' => $singleRecord['gmtOffsetName'],
                'abbreviation' => $singleRecord['abbreviation'],
                'tz_name' => $singleRecord['tzName'],
            ]);
        }
    }

    /**
     * remove certain characters from the strings inside the array, for example an array has strings,
     * and we want to remove certain or set of characters and replace by another character or string,
     * like ['one dark', 'darkula', 'oceanic next'] , we want to remove 'a' or replace 'a' by 'c'
     * @param $hayStack
     * @param array $charsArray
     * @param string $character
     * @return array the array with replace strings
     */
    private function removeCharacters($hayStack, array $charsArray, $character = ''): array {
        $tempArray = [];
        foreach ($hayStack as $item) {
            $tempArray[] = str_replace($charsArray, $character, $item);
        }
        return $tempArray;
    }

    /**
     * remove certain special characters from strings
     * @param string $str from to remove
     * @return string the trimmed, replaced string
     */
    public function fixJson(string $str): string {
        return preg_replace(
            '/(?<=(\{|\,))(\w+)(?=\:)/',
            '"$2"',
            str_replace("'", '"', $str)
        );
    }

    /**
     * removed
     * @param array $stringsArray
     * @return array
     */
    private function fixJsonString(array $stringsArray): array {
        foreach ($stringsArray as $iValue) {
            if (!str_contains($iValue, '}')) {
                $stringsArray[] = $iValue . '}';
            }
        }
        return $stringsArray;
    }

}
