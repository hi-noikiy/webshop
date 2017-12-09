<?php

namespace WTG\Http\Controllers\Checkout;

use Illuminate\Http\Request;
use WTG\Models\Product;
use WTG\Contracts\CartContract;
use Illuminate\Http\JsonResponse;
use WTG\Contracts\ProductContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\AddToCartRequest;
use WTG\Http\Requests\UpdateCartRequest;
use WTG\Http\Requests\DeleteItemFromCartRequest;

/**
 * Cart controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CartController extends Controller
{
    /**
     * Cart overview page.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        $previousUrl = url()->previous();

        if (! preg_match('/.*checkout/', $previousUrl)) {
            session()->flash('continue.url', $previousUrl);
        }

        return view('pages.checkout.cart');
    }

    /**
     * Add a product to the cart.
     *
     * @param  AddToCartRequest  $request
     * @param  CartContract  $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function putAction(AddToCartRequest $request, CartContract $cart)
    {
        $sku = $request->input('product');
        $product = $this->findProduct($sku);

        if (! $product) {
            return $this->productNotFoundResponse($sku);
        }

        $cart->loadForCustomer($request->user());
        $cart->addProduct($product, $request->input('quantity'));

        return response([
            'message' => __('Het product is toegevoegd aan uw winkelwagen.'),
            'success' => true,
            'count' => $cart->count(),
            'code' => 200
        ]);
    }

    /**
     * Update the cart.
     *
     * @param  UpdateCartRequest  $request
     * @param  CartContract  $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(UpdateCartRequest $request, CartContract $cart)
    {
        $sku = $request->input('sku');
        $product = $this->findProduct($sku);

        if (! $product) {
            return $this->productNotFoundResponse($sku);
        }

        $cart->loadForCustomer($request->user());
        $cart->updateProduct($product, $request->input('quantity'));

        return response([
            'success' => true,
            'code' => 200
        ]);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  Request  $request
     * @param  CartContract  $cart
     * @param  string  $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(Request $request, CartContract $cart, string $sku)
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return $this->productNotFoundResponse($sku);
        }

        $cart->loadForCustomer($request->user());
        $cart->removeProduct($product);

        return response([
            'message' => __('Het product is verwijderd uit uw winkelwagen.'),
            'success' => true,
            'code' => 200
        ]);
    }

    /**
     * Find a product.
     *
     * @param  string  $sku
     * @return null|Product
     */
    protected function findProduct(string $sku): ?Product
    {
        return app()->make(ProductContract::class)->findBySku($sku);
    }

    /**
     * Send a product not found response.
     *
     * @param  string  $sku
     * @return \Illuminate\Http\JsonResponse
     */
    protected function productNotFoundResponse(string $sku): JsonResponse
    {
        return response([
            'message' => __('Geen product gevonden met sku :sku', ['sku' => $sku]),
            'success' => false,
            'code' => 400
        ]);
    }
}