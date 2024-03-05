<?php

namespace GiorgioSpa\Console;

use GiorgioSpa\Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class InitDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spa:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'spa init database';

    public function handle(): void
    {
        $this->call('migrate', [
            '--path' => '/database/migrations/admin/',
        ]);
        $this->call('db:seed', [
            '--class' => DatabaseSeeder::class,
        ]);
    }
}
