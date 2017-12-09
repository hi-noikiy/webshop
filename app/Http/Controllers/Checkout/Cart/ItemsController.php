<?php

namespace WTG\Http\Controllers\Checkout\Cart;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Contracts\CartContract;
use WTG\Contracts\CartItemContract;
use WTG\Http\Controllers\Controller;
use WTG\Soap\GetProductPricesAndStocks\Response;

/**
 * Checkout cart items controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout\Cart
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ItemsController extends Controller
{
    /**
     * Get the items in the cart.
     *
     * @param  Request  $request
     * @param  CartContract  $cart
     * @return \Illuminate\Support\Collection
     */
    public function getAction(Request $request, CartContract $cart)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $cart->loadForCustomer($customer);

        $items = $cart->items();
        $products = $items->pluck('product');

        /** @var Response $response */
        $response = app('soap')->getProductPricesAndStocks($products, $customer->getCustomerNumber());

        if ($response->code !== 200) {
            return response()->json([
                'payload' => $items
            ]);
        }

        $products = collect($response->products);

        $items->map(function (CartItemContract $item) use ($products) {
            $product = $products->first(function ($product) use ($item) {
                return $product->sku === $item->getProduct()->sku();
            });

            if (! $product) {
                return $item;
            }

            $item->setAttribute('price', $product->net_price);
            $item->setAttribute('subtotal', format_price($product->net_price * $item->getQuantity()));

            return $item;
        });

        return response()->json([
            'payload' => [
                'items' => $items,
                'grandTotal' => format_price($items->sum(function (CartItemContract $item) {
                    return $item->getAttribute('price') * $item->getQuantity();
                }))
            ]
        ]);
    }
}