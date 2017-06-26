<?php

namespace Ms48\LaravelConsoleProgressBar;

use Illuminate\Support\ServiceProvider;

class ConsoleProgressBarProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {        
        $this->app->bind('consoleProgressBar', function () {
            return new ConsoleProgressBar();
        });
    }
}
