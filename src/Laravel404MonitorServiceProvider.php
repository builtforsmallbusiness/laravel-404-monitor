<?php

namespace BuiltForSmallBusiness\Laravel404Monitor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class Laravel404MonitorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/404monitor.php',
            '404monitor'
        );
    }

   public function boot(): void
    {
        $this->publishAssets();
        $this->loadMigrations();
        $this->loadViews();
        $this->registerRoutes();
        $this->registerMiddleware();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InfoCommand::class,
            ]);
        }
    }

    protected function printSuccessMessage(): void
    {
        $this->callAfterResolving('events', function () {
            $prefix = config('404monitor.route_prefix', '_404-monitor');
            $url = url($prefix);

            \Illuminate\Support\Facades\Artisan::command('404monitor:info', function () use ($url) {
                $this->info("✅ 404 Monitor is active.");
                $this->line("   Dashboard → {$url}");
                $this->line("   Publish config: php artisan vendor:publish --tag=404monitor-config");
            });
        });
    }

    protected function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../config/404monitor.php' => config_path('404monitor.php'),
        ], '404monitor-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], '404monitor-migrations');

        $this->publishes([
            __DIR__ . '/../resources/views/' => resource_path('views/vendor/404monitor'),
        ], '404monitor-views');
    }

    protected function loadMigrations(): void
    {
        if (config('404monitor.auto_migrate', false)) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    protected function loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', '404monitor');
    }

    protected function registerRoutes(): void
    {
        if (! config('404monitor.dashboard_enabled', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    protected function registerMiddleware(): void
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(Middleware\Track404Middleware::class);
    }
}
