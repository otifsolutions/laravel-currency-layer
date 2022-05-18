<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use OTIFSolutions\CurrencyLayer\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class RemoveHistoricalRates extends Command {

    protected $signature = 'rates:delete';
    protected $description = 'Will remove or partially remove the exchange rates from exchange rates table';

    public function handle() {
        $accessKey = Setting::get('crkey');

        if (!isset($accessKey)) {
            $this->warn('accessKey is not set. Set it first and re-run the command');
            return;
        }

        $ratesSaveDays = Setting::get('days_rates');   // data of how many days we want to store

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('numDays should be a positive integer and not a character string');
            return;
        }

        if (CurrencyRate::exists()) {
            CurrencyRate::whereDate('created_at', '<=', Carbon::now()->subDays($ratesSaveDays))->delete();
            $this->info('exchange rates synced successfully');
        }

    }
}
