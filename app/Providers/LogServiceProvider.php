<?php

namespace WTG\Providers;

use Illuminate\Support\ServiceProvider;
use WTG\LogHandlers\DatabaseLogHandler;

class LogServiceProvider extends ServiceProvider
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
        \Log::getMonolog()->pushHandler(new DatabaseLogHandler);
    }
}
