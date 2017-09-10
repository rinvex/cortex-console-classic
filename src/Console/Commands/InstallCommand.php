<?php

declare(strict_types=1);

namespace Cortex\Console\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install:console';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Console Module.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Install cortex/console:');
        $this->call('cortex:publish:console');
    }
}
