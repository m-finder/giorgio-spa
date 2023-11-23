<?php

namespace GiorgioSpa\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class CleanPackageCommand extends Command
{
    protected $signature = 'package:clean';

    protected $description = 'Clean up configuration files from the package.';

    public function handle(): void
    {
        // 删除配置文件
        File::delete([
            config_path('giorgio.php'),
            config_path('sanctum.php'),
            config_path('captcha.php'),
            config_path('permission.php'),
            base_path('routes/giorgio.php'),
            database_path('migrations/admin'),
            base_path('resources/scripts')
        ]);

        $this->info('Package configuration files cleaned up successfully.');
    }
}