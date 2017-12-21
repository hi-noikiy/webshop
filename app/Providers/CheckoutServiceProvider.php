<?php

namespace WTG\Providers;

use WTG\Models\Quote;
use WTG\Models\QuoteItem;
use WTG\Services\CartService;
use Illuminate\Support\Facades\View;
use WTG\Contracts\Models\CartContract;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\CartServiceContract;

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
        $this->app->bind(CartServiceContract::class, CartService::class);

        $this->app->singleton(CartContract::class, function () {
            return new Quote();
        });

        View::composer('*', function ($view) {
            if (auth()->check()) {
                /** @var CustomerContract $customer */
                $customer = auth()->user();

                $view->with('cart', app()->make(CartContract::class)->loadForCustomer($customer));
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