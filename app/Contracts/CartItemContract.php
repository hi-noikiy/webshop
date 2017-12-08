<?php

namespace WTG\Contracts;

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
     * @param  ProductContract  $product
     * @return ProductContract
     */
    public function setProduct(ProductContract $product): ProductContract;

    /**
     * Get the product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract;

    /**
     * Set the cart.
     *
     * @param  CartContract  $cart
     * @return CartContract
     */
    public function setCart(CartContract $cart): CartContract;

    /**
     * Get the cart.
     *
     * @return CartContract
     */
    public function getCart(): CartContract;

    /**
     * Set the item quantity.
     *
     * @param  float  $quantity
     * @param  bool  $replace  If false, the given qty should be added to the current qty.
     * @return float
     */
    public function setQuantity(float $quantity, bool $replace = true): float;

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQuantity(): float;
}