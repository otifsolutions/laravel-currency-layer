<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishResources extends Command {

    protected $signature = 'publish:flags';

    protected $description = 'Publish all the resources of currency layer package into your project';

    public function handle() {

        Artisan::call('vendor:publish --tag=otif-flags');

        $this->info('flags published to public/flags');
        $this->newLine();

        return 0;
    }
}
