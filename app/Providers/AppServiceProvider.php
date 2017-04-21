<?php

namespace App\Providers;

use Doctrine\DBAL\Types\Type;
use App\Services\HelperService;
use App\Services\FormatService;
use App\Services\DownloadService;
use App\Services\DiscountFileService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');
        }

        $this->app->singleton('format', FormatService::class);
        $this->app->singleton('helper', HelperService::class);

        $this->app->bind('download', DownloadService::class);
        $this->app->bind('discount_file', DiscountFileService::class);
    }
}
