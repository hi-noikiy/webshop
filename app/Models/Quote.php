<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\CartContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Exceptions\CartUpdateException;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Quote model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Quote extends Model implements CartContract
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Customer relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Quote item relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quoteItems()
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Get the cart address.
     *
     * @return null|AddressContract
     */
    public function getAddress(): ?AddressContract
    {
        $address = $this->getAttribute('address');

        if (! $address) {
            /** @var Customer $customer */
            $customer = $this->getAttribute('customer');
            $address = $customer->getContact()->defaultAddress();
        }

        return $address;
    }

    /**
     * Set the delivery address.
     *
     * @param  AddressContract  $address
     * @return AddressContract
     */
    public function setAddress(AddressContract $address): AddressContract
    {
        $this->address()->associate($address);

        return $address;
    }

    /**
     * Find or create a cart for a customer.
     *
     * @param  CustomerContract  $customer
     * @return CartContract
     */
    public function loadForCustomer(CustomerContract $customer): CartContract
    {
        $this->forceFill(
            $this->firstOrCreate([
                'customer_id' => $customer->identifier()
            ])->toArray()
        );

        $this->exists = true;

        return $this;
    }

    /**
     * Get or set the product identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getKey();
    }

    /**
     * Add a product to the cart.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     */
    public function addProduct(ProductContract $product, float $quantity = 1.0): CartItemContract
    {
        $item = $this->findProduct($product);

        if ($item) {
            $item->quantity($quantity + $item->quantity());
        } else {
            /** @var CartItemContract|Model $item */
            $item = app()->make(CartItemContract::class);
            $item->setProduct($product);
            $item->quantity($quantity);
            $item->cart($this);
        }

        if ($item->save()) {
            // Update model timestamp to indicate the cart was updated.
            $this->touch();

            return $item;
        }

        $this->throwFailedCartException();
    }

    /**
     * Update a cart item.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return CartItemContract
     * @throws CartUpdateException
     */
    public function updateProduct(ProductContract $product, float $quantity): CartItemContract
    {
        $item = $this->findProduct($product);
        $item->quantity($quantity);

        if ($item->save()) {
            // Update model timestamp to indicate the cart was updated.
            $this->touch();

            return $item;
        }

        $this->throwFailedCartException();
    }

    /**
     * Remove a product from the cart.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function removeProduct(ProductContract $product): bool
    {
        if (($item = $this->findProduct($product)) === null) {
            return true;
        }

        return $item->delete();
    }

    /**
     * Find a cart item by product.
     *
     * @param  ProductContract  $product
     * @return null|CartItemContract
     */
    public function findProduct(ProductContract $product): ?CartItemContract
    {
        return $this->items()->first(function (CartItemContract $item) use ($product) {
            return $item->getProduct()->identifier() === $product->identifier();
        });
    }

    /**
     * Check if the product is in the cart.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasProduct(ProductContract $product): bool
    {
        return (bool) $this->findProduct($product);
    }

    /**
     * Cart item collection.
     *
     * @return Collection
     */
    public function items(): Collection
    {
        return $this->quoteItems()->with('product')->get();
    }

    /**
     * Cart item count.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->items()->count();
    }

    /**
     * Throw a cart update exception.
     *
     * @throws CartUpdateException
     * @return void
     */
    protected function throwFailedCartException(): void
    {
        throw new CartUpdateException('An error occurred while saving a cart.');
    }
}