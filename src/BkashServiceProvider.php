<?php

namespace Ps\Bkash;

use Illuminate\Support\ServiceProvider;

class BkashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         $this->publishes([
             __DIR__ . '/Config/bkash.php' => config_path('bkash.php'),
         ]);
        $this->loadroutesfrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'bkash');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBkash();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerBkash()
    {
        // $this->app->singleton('Bkash', function ($app) {
        //     return new Bkash();
        // });

    }

}
