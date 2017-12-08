<?php

namespace WTG\Providers;

use WTG\Models\Quote;
use WTG\Models\QuoteItem;
use WTG\Contracts\CartContract;
use WTG\Contracts\CartItemContract;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Checkout service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * @var
     */
    protected $quote;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CartContract::class, Quote::class);
        $this->app->bind(CartItemContract::class, QuoteItem::class);

        $this->app->singleton(CartContract::class, function () {
            return new Quote();
        });

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('cart', app()->make(CartContract::class)->loadForCustomer(auth()->user()));
            }
        });
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