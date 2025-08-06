<?php

namespace LaravelUnisender;

use LaravelUnisender\Services\UnisenderService;
use Illuminate\Support\ServiceProvider;

class UnisenderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/unisender.php', 'unisender'
        );

        $this->app->singleton(UnisenderService::class, function ($app) {
            return new UnisenderService();
        });

        $this->app->alias(UnisenderService::class, 'unisender');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/unisender.php' => config_path('unisender.php'),
            ], 'unisender-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'unisender-migrations');

            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [UnisenderService::class, 'unisender'];
    }
} 