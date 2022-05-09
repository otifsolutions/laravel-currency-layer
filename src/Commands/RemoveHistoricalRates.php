<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use OTIFSolutions\CurrencyLayer\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class RemoveHistoricalRates extends Command {

    protected $signature = 'rates:delete';
    protected $description = 'Remove all the exchange rates';

    public function handle() {

        $ratesSaveDays = Setting::get('days_rates');   // data of how many days we want to store

        if (!(is_numeric($ratesSaveDays) && is_numeric(abs($ratesSaveDays)))) {
            $this->warn('The key should be a positive integer and not a character string');
            return;
        }

        if (CurrencyRate::exists()) {
            CurrencyRate::whereDate('created_at', '<=', Carbon::now()->subDays($ratesSaveDays))->delete();
            $this->info('exchange rates synced successfully');
        }

    }
}
