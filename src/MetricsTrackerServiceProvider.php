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

        // Publishable assets
        $this->publishes([
            __DIR__.'/../public/js/metrics-tracker.js' => public_path('package/alifahmmed/metrics-tracker.js'),
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'public');

    }
}

