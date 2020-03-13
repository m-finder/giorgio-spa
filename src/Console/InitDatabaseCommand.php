<?php

namespace GiorgioSpa\Console;

use GiorgioSpa\Seeder\DatabaseSeeder;
use Illuminate\Console\Command;

class InitDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spa init database';


    public function handle()
    {
        $this->call('migrate');
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
    }
}
