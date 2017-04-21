<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Elasticsearch\ClientBuilder;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('elastic', function () {
            return ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}