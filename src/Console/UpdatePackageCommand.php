<?php

namespace GiorgioSpa\Console;

use GiorgioSpa\Presets\Spa;
use Illuminate\Console\Command;


class UpdatePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spa update package';


    public function handle()
    {
        $this->call('ui', ['type' => 'vue']);
        Spa::install(false);
    }
}
