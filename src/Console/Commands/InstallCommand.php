<?php
namespace Giorgio\Console\Commands;

use Giorgio\Providers\GiorgioServiceProvider;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * @var string
     */
    protected $description = 'admin installer';

    public function handle()
    {
        $this->info("installing armani admin");
        if (file_exists(config_path('admin.php')) && !$this->confirm('files already exists, overwrite?')) {
            $this->info('canceled');
            exit;
        }
        $this->install();
    }

    private function install()
    {
        $this->comment('publishing sources...');
        $this->call('vendor:publish', [
            '--force' => true,
            '--provider' => GiorgioServiceProvider::class,
        ]);
    }
}
