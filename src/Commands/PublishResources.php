<?php

namespace OTIFSolutions\CurrencyLayer\Commands;

use Artisan;
use Illuminate\Console\Command;

class PublishResources extends Command {
    protected $signature = 'publish:resources';

    protected $description = 'Publish all the resources of currency layer package into your project';

    public function handle() {

        Artisan::call('vendor:publish --tag=otif-config');
        Artisan::call('vendor:publish --tag=otif-seeder');
        Artisan::call('vendor:publish --tag=otif-flags');

        $this->newLine();
        $this->line('resources published');
        $this->newLine();

        return 0;
    }
}
