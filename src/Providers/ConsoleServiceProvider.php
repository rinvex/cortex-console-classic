<?php

declare(strict_types=1);

namespace Cortex\Console\Providers;

use Cortex\Console\Services\Terminal;
use Cortex\Console\Console\Commands\Vi;
use Illuminate\Support\ServiceProvider;
use Cortex\Console\Console\Commands\Find;
use Cortex\Console\Console\Commands\Tail;
use Cortex\Console\Console\Commands\Mysql;
use Cortex\Console\Console\Commands\Artisan;
use Cortex\Console\Console\Commands\Composer;
use Cortex\Console\Console\Commands\ArtisanTinker;
use Cortex\Console\Console\Commands\InstallCommand;
use Cortex\Console\Console\Commands\PublishCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
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
    public function register()
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
                Vi::class,
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
    public function boot()
    {
        // Load resources
        require __DIR__.'/../../routes/breadcrumbs.php';
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/console');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/console');
        $this->app->afterResolving('blade.compiler', function () {
            require __DIR__.'/../../routes/menus.php';
        });

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/console')], 'cortex-console-lang');
        $this->publishes([realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/console')], 'cortex-console-views');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, function ($app) use ($key) {
                return new $key();
            });
        }

        $this->commands(array_values($this->commands));
    }
}
