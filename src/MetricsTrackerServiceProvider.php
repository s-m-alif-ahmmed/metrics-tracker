<?php

namespace AlifAhmmed\MetricsTracker;

use Illuminate\Support\ServiceProvider;
use AlifAhmmed\MetricsTracker\Helpers\MetricsTracker;

class MetricsTrackerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the helper class as a singleton
        $this->app->singleton('helper', function () {
            return new MetricsTracker();
        });
    }

    public function boot()
    {
        // Routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');


        // Publishable assets
        $this->publishes([
            __DIR__.'/../public/js/metrics-tracker.js' => public_path('vendor/alifahmmed/metrics-tracker.js'),
        ], 'public');

    }
}

