<?php

namespace GiorgioSpa\Console;

use GiorgioSpa\Providers\GiorgioServiceProvider;
use Illuminate\Console\Command;

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


    public function handle(): void
    {
        $this->info("installing armani admin");
        if (file_exists(config_path('giorgio.php')) && !$this->confirm('files already exists, overwrite?')) {
            $this->info('canceled');
            exit;
        }
        $this->install();
    }

    public function install(): void
    {
        $this->comment('publishing sources...');
        $this->call('vendor:publish', [
            '--force' => true,
            '--provider' => GiorgioServiceProvider::class,
        ]);
    }
}
