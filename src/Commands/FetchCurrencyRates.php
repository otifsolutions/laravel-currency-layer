<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use Illuminate\Console\Command;
use OTIFSolutions\CurlHandler\Curl;
use OTIFSolutions\CurrencyLayer\Models\Currency;
use OTIFSolutions\CurrencyLayer\Models\CurrencyRate;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class FetchCurrencyRates extends Command {

    protected $signature = 'rates:get';
    protected $description = 'Hits the currency layer api, fetch the exchange rates';

    public function handle() {

        $accessKey = Setting::get('crlkey');
        $ratesSaveDays = Setting::get('daysRates');   // data of how many days we want to store

        if (!isset($accessKey, $ratesSaveDays)) {
            $this->warn('Either accessy key \'crlkey\' or rates save days \'daysRates\' is not set. Check it how README.md');
            return;
        }

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('daysRates key should be a positive integer');
            return;
        }

        if (Currency::all()->count() === 0) {
            $this->warn('Currency Table is blank | Can\'t run the command');
            $this->line('Populating the tables...');
            $this->newLine();
            Artisan::call('fill:tables');
            $this->line('Now re-run this command');
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

        $bar = $this->output->createProgressBar(count($response['quotes']));
        $bar->start();


        $sourceCrrName = $response['source'];
        $sourceObj = Currency::firstWhere('currency', $sourceCrrName);

        foreach ($response['quotes'] as $i => $exchangeRate) {
            $convertedToCrrName = substr($i, 3, 6);

            $currencyObj = Currency::firstWhere('currency', $convertedToCrrName);
            if ($currencyObj) {
                CurrencyRate::create([
                    'currency_id' => $currencyObj->id,
                    'source_crr' => $sourceCrrName,
                    'converted_crr' => $convertedToCrrName,
                    'exchange_rate' => $exchangeRate,
                    'source_currency_id' => $sourceObj->id
                ]);

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Exchange rates synced successfully');

    }
}
