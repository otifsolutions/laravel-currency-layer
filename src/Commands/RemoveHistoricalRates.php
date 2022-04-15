<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use OTIFSolutions\Laravel\Settings\Models\Setting;

class RemoveHistoricalRates extends Command {

    protected $signature = 'rates:delete';
    protected $description = 'Remove all the exchange rates';

    public function handle() {

        $days = Setting::get('rates_save_days');
        if (CurrencyRate::exists()) {
            CurrencyRate::whereDate('created_at', '<=', Carbon::now()->subDays($days))->delete();
            $this->info('Old exchange rates removed');
        } else {
            $this->info('Exchange rates table is Empty Already');
        }

    }
}
