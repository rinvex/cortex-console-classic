<?php

declare(strict_types=1);

namespace Cortex\Console\Providers;

use Cortex\Console\Services\Terminal;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Console\Console\Commands\Find;
use Cortex\Console\Console\Commands\Tail;
use Cortex\Console\Console\Commands\Mysql;
use Cortex\Console\Console\Commands\Artisan;
use Cortex\Console\Console\Commands\Composer;
use Cortex\Console\Console\Commands\SeedCommand;
use Cortex\Console\Console\Commands\ArtisanTinker;
use Cortex\Console\Console\Commands\UnloadCommand;
use Cortex\Console\Console\Commands\InstallCommand;
use Cortex\Console\Console\Commands\PublishCommand;
use Cortex\Console\Console\Commands\ActivateCommand;
use Cortex\Console\Console\Commands\AutoloadCommand;
use Cortex\Console\Console\Commands\DeactivateCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        ActivateCommand::class => 'command.cortex.console.activate',
        DeactivateCommand::class => 'command.cortex.console.deactivate',
        AutoloadCommand::class => 'command.cortex.console.autoload',
        UnloadCommand::class => 'command.cortex.console.unload',

        SeedCommand::class => 'command.cortex.console.seed',
        PublishCommand::class => 'command.cortex.console.publish',
        InstallCommand::class => 'command.cortex.console.install',
    ];

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        // Register console commands
        $this->registerCommands($this->commands);

        $this->app->singleton(Terminal::class, function ($app) {
            $_SERVER['PHP_SELF'] = 'artisan'; // Fix (index.php => artisan)

            return new Terminal($app, $app['events'], $app->version());
        });

        if (! $this->app->runningInConsole()) {
            $commands = [
                Find::class,
                Artisan::class,
                ArtisanTinker::class,
                Composer::class,
                Mysql::class,
                Tail::class,
            ];

            Terminal::starting(function ($artisan) use ($commands) {
                $artisan->resolveCommands($commands);
            });
        }
    }
}
