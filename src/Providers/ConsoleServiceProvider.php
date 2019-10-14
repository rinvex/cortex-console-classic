<?php

declare(strict_types=1);

namespace Cortex\Console\Providers;

use Cortex\Console\Services\Terminal;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Console\Console\Commands\Find;
use Cortex\Console\Console\Commands\Tail;
use Cortex\Console\Console\Commands\Mysql;
use Illuminate\Contracts\Events\Dispatcher;
use Cortex\Console\Console\Commands\Artisan;
use Cortex\Console\Console\Commands\Composer;
use Cortex\Console\Console\Commands\SeedCommand;
use Cortex\Console\Console\Commands\ArtisanTinker;
use Cortex\Console\Console\Commands\InstallCommand;
use Cortex\Console\Console\Commands\PublishCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
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
        ! $this->app->runningInConsole() || $this->registerCommands();

        $this->app->singleton(Terminal::class, function ($app) {
            $_SERVER['PHP_SELF'] = 'artisan'; // Fix (index.php => artisan)
            $artisan = new Terminal($app, $app['events'], $app->version());

            return $artisan;
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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $dispatcher): void
    {
        // Load resources
        $this->loadRoutesFrom(__DIR__.'/../../routes/web/adminarea.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/console');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/console');

        $this->app->runningInConsole() || $dispatcher->listen('accessarea.ready', function ($accessarea) {
            ! file_exists($menus = __DIR__."/../../routes/menus/{$accessarea}.php") || require $menus;
            ! file_exists($breadcrumbs = __DIR__."/../../routes/breadcrumbs/{$accessarea}.php") || require $breadcrumbs;
        });

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishesLang('cortex/console', true);
        ! $this->app->runningInConsole() || $this->publishesViews('cortex/console', true);
    }
}
