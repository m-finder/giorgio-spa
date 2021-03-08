<?php


namespace Giorgio\Console\Commands;


use Giorgio\Seeder\AdminTableSeeder;
use Illuminate\Console\Command;

class MigrationCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'admin:migrate {--force} {--seed}';

    /**
     * @var string
     */
    protected $description = 'admin table migrate';

    public function handle()
    {
        $force = $this->option('force');
        $seed = $this->option('seed');

        if ($force) {
            $this->confirm("force migrate?");
        }

        $this->call('migrate:refresh', [
            '--force' => $force,
            '--path' => '/database/migrations/admin'
        ]);

        if ($seed) {
            $this->call('db:seed', [
                '--class' => AdminTableSeeder::class
            ]);
        }
    }
}
