<?php

namespace WTG\Http\Controllers\Checkout\Cart;

use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Services\CartServiceContract;

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
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * CartController constructor.
     *
     * @param  CartServiceContract  $cartService
     */
    public function __construct(CartServiceContract $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Get the items in the cart.
     *
     * @param  Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getAction(Request $request)
    {
        $items = $this->cartService->getItems(
            $request->user(),
            true
        );

        return response()->json([
            'payload' => [
                'items' => $items,
                'grandTotal' => format_price($items->sum(function (CartItemContract $item) {
                    return str_replace(',', '.', $item->price()) * $item->quantity();
                }))
            ]
        ]);
    }
}