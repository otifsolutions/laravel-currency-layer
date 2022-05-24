<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use OTIFSolutions\CurrencyLayer\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class RemoveHistoricalRates extends Command {

    protected $signature = 'rates:delete';
    protected $description = 'Command will remove older data from rates_days days';

    public function handle() {

        $accessKey = Setting::get('crlKey');
        $ratesSaveDays = Setting::get('daysRates');   // data of how many days we want to store

        if (!isset($accessKey, $ratesSaveDays)) {
            $this->warn('Either accessy key \'crlKey\' or rates save days \'daysRates\' is not set. Check it how README.md');
            return;
        }

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('daysRates key should be a positive integer');
            return;
        }


        if (CurrencyRate::exists()) {
            CurrencyRate::whereDate('created_at', '<=', Carbon::now()->subDays($ratesSaveDays))->delete();
            $this->info('exchange rates synced successfully');
            $this->newLine();
        }

    }
}
