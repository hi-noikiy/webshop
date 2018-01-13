<?php

namespace WTG\Models;

use WTG\Contracts\Models\CartContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Quote item model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class QuoteItem extends Model implements CartItemContract
{
    /**
     * Quote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get or set the product.
     *
     * @param  ProductContract|null  $product
     * @return ProductContract|null
     */
    public function setProduct(ProductContract $product = null): ?ProductContract
    {
        if ($product) {
            $this->product()->associate($product);
        }

        return $this->getAttribute('product');
    }

    /**
     * Get the product.
     *
     * @return null|ProductContract
     */
    public function getProduct(): ?ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Get or set the quantity.
     *
     * @param  float|null  $quantity
     * @return float
     */
    public function quantity(float $quantity = null): float
    {
        if ($quantity !== null) {
            $this->setAttribute('qty', $quantity);
        }

        return $this->getAttribute('qty');
    }

    /**
     * Get or set the cart.
     *
     * @param  CartContract|null  $cart
     * @return CartContract
     */
    public function cart(CartContract $cart = null): CartContract
    {
        if ($cart) {
            $this->quote()->associate($cart);
        }

        return $this->getAttribute('quote');
    }

    /**
     * Get or set the price.
     *
     * @param  string|null  $price
     * @return string
     */
    public function price(string $price = null): string
    {
        if ($price !== null) {
            $this->setAttribute('price', $price);
        }

        return $this->getAttribute('price');
    }

    /**
     * Get or set the subtotal.
     *
     * @param  string|null  $subtotal
     * @return string
     */
    public function subtotal(string $subtotal = null): string
    {
        if ($subtotal !== null) {
            $this->setAttribute('subtotal', $subtotal);
        }

        return $this->getAttribute('subtotal');
    }
}