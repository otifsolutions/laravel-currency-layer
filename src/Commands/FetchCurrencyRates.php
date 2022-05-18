<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use Illuminate\Console\Command;
use OTIFSolutions\CurlHandler\Curl;
use OTIFSolutions\CurrencyLayer\Models\{Currency, CurrencyRate};
use OTIFSolutions\Laravel\Settings\Models\Setting;

class FetchCurrencyRates extends Command {

    protected $signature = 'rates:get';
    protected $description = 'Hits the currency layer api, fetch the exchange rates';

    public function handle() {
        $accessKey = Setting::get('crkey');

        if (!isset($accessKey)) {
            $this->warn('accessKey is not set. Set it first and re-run the command');
            return;
        }

        $ratesSaveDays = Setting::get('days_rates');   // data of how many days we want to store

        if (!isset($ratesSaveDays)) {
            $this->warn('ratesSaveDays is not set. Set it first then re-run the command');
            return;
        }

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('numDays should be a positive integer and not a character string');
            return;
        }

        if (Currency::all()->count() === 0) {
            $this->warn('Currency Table is blank | Seeders Started Running ...');
            $this->newLine("****************************");
            $this->newLine("*****  Seeders Running *****");
            $this->newLine("****************************");
            \Artisan::call('db:seed --class=\\OTIFSolutions\\CurrencyLayer\\Database\\Seeders\\CurrencySeeder');
            $this->newLine();
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
            $bar->advance();
        }

        $bar->finish();

        $this->info('Exchange rates synced successfully');

    }
}
