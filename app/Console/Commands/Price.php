<?php

namespace WTG\Console\Commands;

use WTG\Models\Product;
use WTG\Models\Customer;
use Illuminate\Console\Command;

class Price extends Command
{
    protected $signature = 'price {product}';

    protected $description = 'Get the price data for a product';

    public function handle()
    {
        $products = Product::where('sku', $this->input->getArgument('product'))->get();
        $customer = Customer::where('username', '99999')->first();

        dd(app('soap')->getProductPricesAndStocks($products, $customer));
    }
}