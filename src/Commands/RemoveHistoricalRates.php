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

        $ratesSaveDays = Setting::get('rates_save_days');

        if (is_null($ratesSaveDays)) {
            $this->warn('key rates_save_days cannot be NULL but in-ranged positive integer');
            return;
        }

        if (is_string($ratesSaveDays)) {
            $this->warn('key rates_save_days must not be a string but an integer');
            return;
        }

        if (!is_integer($ratesSaveDays)) {
            $this->warn('key rates_save_days must be an integer');
            return;
        }

        if ($ratesSaveDays < 1) {
            $this->warn('key rates_save_days must not be negative');
            return;
        }

        if ($ratesSaveDays > 30) {
            $this->warn('key rates_save_days must be between 1 - 30');
            return;
        }

        if (CurrencyRate::exists()) {
            CurrencyRate::whereDate('created_at', '<=', Carbon::now()->subDays($ratesSaveDays))->delete();
            $this->info('Old exchange rates removed');
        } else {
            $this->info('Exchange rates table is Empty Already');
        }

    }
}
