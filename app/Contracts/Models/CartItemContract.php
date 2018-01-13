<?php

namespace WTG\Contracts\Models;

/**
 * Cart item contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CartItemContract
{
    /**
     * Set the product.
     *
     * @param  ProductContract|null  $product
     * @return ProductContract|null
     */
    public function setProduct(ProductContract $product = null): ?ProductContract;

    /**
     * Get the product.
     *
     * @return null|ProductContract
     */
    public function getProduct(): ?ProductContract;

    /**
     * Set the cart.
     *
     * @param  CartContract|null  $cart
     * @return CartContract
     */
    public function cart(CartContract $cart = null): CartContract;

    /**
     * Get or set the item quantity.
     *
     * @param  float|null  $quantity
     * @return float
     */
    public function quantity(float $quantity = null): float;

    /**
     * Get or set the price.
     *
     * @param  string|null  $price
     * @return string
     */
    public function price(string $price = null): string;

    /**
     * Get or set the subtotal.
     *
     * @param  string|null  $subtotal
     * @return string
     */
    public function subtotal(string $subtotal = null): string;
}