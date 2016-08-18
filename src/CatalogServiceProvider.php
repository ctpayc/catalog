<?php

namespace Liteweb\Catalog;

use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('liteweb-catalog', function() {
            return new Catalog;
        });
    }

    public function boot()
    {
        require __DIR__ . '/Http/routes.php';

        $this->loadViewsFrom(__DIR__ . '/views', 'liteweb-catalog');

        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vendor/liteweb-catalog'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/database/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/database/seeds' => $this->app->databasePath() . '/seeds'
        ], 'seeds');

        $this->publishes([
            __DIR__ . '/default_params' => $this->app->storagePath() . '/app/default_params'
        ], 'default_params');

        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/liteweb-catalog'),
        ], 'public');
    }
}