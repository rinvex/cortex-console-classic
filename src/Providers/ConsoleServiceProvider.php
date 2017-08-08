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

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load resources
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/console');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/console');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();

        // Register sidebar menus
        $this->app->singleton('menus.sidebar.console', function ($app) {
            return collect();
        });

        // Register menu items
        $this->app['view']->composer('cortex/foundation::backend.partials.sidebar', function ($view) {
            app('menus.sidebar')->put('console', app('menus.sidebar.console'));
            app('menus.sidebar.console')->put('header', '<li class="header">'.trans('cortex/console::navigation.headers.console').'</li>');
            app('menus.sidebar.console')->put('routes', '<li '.(mb_strpos(request()->route()->getName(), 'backend.routes.') === 0 ? 'class="active"' : '').'><a href="'.route('backend.console.routes.index').'"><i class="fa fa-globe"></i> <span>'.trans('cortex/console::navigation.menus.routes').'</span></a></li>');
            app('menus.sidebar.console')->put('terminal', '<li '.(mb_strpos(request()->route()->getName(), 'backend.terminal.') === 0 ? 'class="active"' : '').'><a href="'.route('backend.console.terminal.form').'"><i class="fa fa-terminal"></i> <span>'.trans('cortex/console::navigation.menus.terminal').'</span></a></li>');
        });
    }

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
        $this->app->singleton(Terminal::class, function ($app) {
            $_SERVER['PHP_SELF'] = 'artisan'; // Fix (index.php => artisan)
            $artisan = new Terminal($app, $app['events'], $app->version());

            return $artisan;
        });

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

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/console')], 'lang');
        $this->publishes([realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/console')], 'views');
    }
}
