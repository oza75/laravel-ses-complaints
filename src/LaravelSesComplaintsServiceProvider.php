<?php

namespace Oza75\LaravelSesComplaints;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Oza75\LaravelSesComplaints\Commands\SubscribeUrlCommand;
use Oza75\LaravelSesComplaints\Contracts\LaravelSesComplaints as Contract;
use Oza75\LaravelSesComplaints\Listeners\CheckIsMessageShouldBeSend;

class LaravelSesComplaintsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-ses-complaints');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-ses-complaints');

//        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-ses-complaints.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-ses-complaints'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-ses-complaints'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-ses-complaints'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                SubscribeUrlCommand::class,
            ]);

//            $this->publishMigrations(['create_sns_subscriptions_table.php', 'create_ses_notifications_table.php']);
        }

        Event::listen(MessageSending::class, CheckIsMessageShouldBeSend::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-ses-complaints');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-ses-complaints', function () {
            return new LaravelSesComplaints;
        });

        $this->app->singleton(Contract::class, LaravelSesComplaints::class);
    }

    /**
     * @param array $paths
     */
    protected function publishMigrations(array $paths)
    {
        $paths = array_filter($paths, function ($path) {
            return empty(glob(database_path("/migrations/*_$path")));
        });

        $toPublish = [];

        foreach ($paths as $path) {
            $toPublish[__DIR__ . '/../database/migrations/' . $path] = database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $path);
        }

        $this->publishes($toPublish, 'migrations');
    }
}
