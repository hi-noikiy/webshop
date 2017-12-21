<?php

namespace WTG\Models;

use WTG\Contracts\Models\CartContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;

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
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Set the product.
     *
     * @param  ProductContract  $product
     * @return ProductContract
     */
    public function setProduct(ProductContract $product): ProductContract
    {
        $this->product()->associate($product);

        return $product;
    }

    /**
     * Get the product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the quantity.
     *
     * @param  float  $quantity
     * @param  bool  $replace
     * @return float
     */
    public function setQuantity(float $quantity, bool $replace = true): float
    {
        if (! $replace) {
            $quantity += $this->getAttribute('qty');
        }

        $this->setAttribute('qty', $quantity);

        return $quantity;
    }

    /**
     * Get the quantity.
     *
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->getAttribute('qty');
    }

    /**
     * Set the cart.
     *
     * @param  CartContract  $cart
     * @return CartContract
     */
    public function setCart(CartContract $cart): CartContract
    {
        $this->quote()->associate($cart);

        return $cart;
    }

    /**
     * Get the cart.
     *
     * @return CartContract
     */
    public function getCart(): CartContract
    {
        return $this->getAttribute('quote');
    }
}