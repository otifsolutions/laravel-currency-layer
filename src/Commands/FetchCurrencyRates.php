<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Console\Command;
use OTIFSolutions\CurlHandler\Curl;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class FetchCurrencyRates extends Command {

    protected $signature = 'rates:get';
    protected $description = 'Hits the currency layer api, fetch the exchange rates';

    public function handle() {
        $accessKey = Setting::get('crkey');

        $ratesSaveDays = Setting::get('days_rates');   // data of how many days we want to store

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('The key should be a positive integer and not a character string');
            return;
        }

        if (Currency::all()->count() === 0) {
            $this->warn('Currency Table is blank | Running the seeders ');
            \Artisan::call('db:seed');
            return;
        }

        $response = Curl::Make()
            ->GET
            ->url('http://api.currencylayer.com/live')
            ->params([
                'access_key' => $accessKey
            ])
            ->execute();

        if (!$response['success']) {
            $this->warn($response['error']['info']);
            return;
        }

        foreach ($response['quotes'] as $i => $value) {
            $baseCurrency = substr($i, 0, 3);
            $childCurrency = substr($i, 3, 6);

            $baseObj = Currency::firstWhere('currency', $childCurrency);
            if ($baseObj) {
                CurrencyRate::create([
                    'currency_id' => $baseObj->id,
                    'baseCr' => $baseCurrency,
                    'childCr' => $childCurrency,
                    'exchange_rates' => $value
                ]);
            }
        }

        $this->info('Exchange rates synced successfully');

    }
}
