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

        // user will set the access accessKey and we will use this access accessKey
        // Setting::set('CURRENCY_LAYER_API_ACCESS_KEY', 'e568ea241bcd6eb1bc61bc9894943f19');

        $accessKey = Setting::get('CURRENCY_LAYER_API_ACCESS_KEY');

        if (!isset($accessKey)) {
            $this->warn('CurrencyLayer API Access key is not set');
            return;
        }

        if (!(strlen($accessKey) === 32)) {
            $this->warn('Access key length is not valid');
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
