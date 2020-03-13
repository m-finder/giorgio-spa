<?php

namespace GiorgioSpa\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spa:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spa installer';


    public function handle()
    {
        $this->call('vendor:publish', [
            '--provider' => 'GiorgioSpa\Providers\GiorgioSpaServiceProvider',
            '--force' => true
        ]);
        $this->call('init:data');
        $this->call('update:package');
    }
}
