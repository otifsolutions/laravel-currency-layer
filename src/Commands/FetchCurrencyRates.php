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


        $accessKey = Setting::get('CURRENCY_LAYER_API_ACCESS_KEY');

        if (Currency::all()->count() === 0) {
            $this->warn('Currency table is blank | Please run the seed first');
            return;
        }

        if (!isset($accessKey)) {
            $this->warn('CURRENCY_LAYER_API_ACCESS_KEY is not set');
            return;
        }

        try {
            $response = Curl::Make()
                ->GET
                ->url('http://api.currencylayer.com/live')
                ->params([
                    'access_key' => $accessKey
                ])
                ->execute();

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Cannot fetch Exchange rates | Check monthly API endpoint hit limit',
                'exceptionCode' => (int)$exception->getCode(),
                'description' => $exception->getMessage()
            ], 400);
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