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

        $accessKey = Setting::get('currency_layer_access_key');

        if (!isset($accessKey)) {
            $this->warn('CurrencyLayer API Access key is not set');
            return;
        }

        if (Currency::all()->count() === 0) {
            $this->warn('Currency table is blank | Please run the seed first');
            return;
        }

        $response = Curl::Make()
            ->GET
            ->url('http://api.currencylayer.com/live')
            ->params([
                'access_key' => $accessKey
            ])
            ->execute();

        if (!isset($response['quotes'])) {
            // fetch the original error from the response and by that error, show it here in war()
            $this->warn('Please check if monthly API hit limit reached');
            return;
        }

        $timesCounter = CurrencyRate::all()->count();

        foreach ($response['quotes'] as $i => $value) {
            $currencyId = Currency::firstWhere('currency', '=', substr($i, '3'));
            if ($currencyId) {
                CurrencyRate::create([
                    'currency_id' => $currencyId->id,
                    'usd_rates' => $value
                ]);
            }
        }

        if ($timesCounter === 0) {
            $this->info('Currency Exchage Rates Added Successfully');
        } else {
            $this->info('Currency Exchange Rates Updated Successfully');
        }

    }
}
