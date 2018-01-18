<?php

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;

/**
 * Interface CartServiceContract
 *
 * @package WTG\Contracts\Services
 */
interface CartServiceContract
{
    /**
     * Add a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(CustomerContract $customer, string $sku, float $quantity = 1.0): ?CartItemContract;

    /**
     * Add a product.
     *
     * @param  CustomerContract  $customer
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProduct(CustomerContract $customer, ProductContract $product, float $quantity = 1.0): ?CartItemContract;

    /**
     * Update a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract;

    /**
     * Delete a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(CustomerContract $customer, string $sku): bool;

    /**
     * Get the cart item count.
     *
     * @param  CustomerContract  $customer
     * @return int
     */
    public function getItemCount(CustomerContract $customer): int;

    /**
     * Get the cart items.
     *
     * @param  CustomerContract  $customer
     * @param  bool  $withPrices
     * @return Collection
     */
    public function getItems(CustomerContract $customer, bool $withPrices = false): Collection;

    /**
     * Get the delivery address of the cart.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDeliveryAddress(CustomerContract $customer): ?AddressContract;

    /**
     * Set the delivery address for the cart.
     *
     * @param  CustomerContract  $customer
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(CustomerContract $customer, AddressContract $address): bool;
}