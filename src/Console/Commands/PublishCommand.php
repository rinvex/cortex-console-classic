<?php

declare(strict_types=1);

namespace Cortex\Console\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:console {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Console Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Publish cortex/console:');
        $this->call('vendor:publish', ['--tag' => 'cortex-console-views', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-console-lang', '--force' => $this->option('force')]);
    }
}
