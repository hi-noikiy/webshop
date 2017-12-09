<?php

namespace WTG\Providers;

use WTG\Models\Product;
use WTG\Contracts\ProductContract;
use Illuminate\Support\ServiceProvider;

/**
 * Catalog service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ProductContract::class, Product::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}