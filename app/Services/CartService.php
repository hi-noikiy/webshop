<?php

namespace WTG\Services;

use WTG\Models\Quote;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\CartServiceContract;

/**
 * Cart service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CartService implements CartServiceContract
{
    /**
     * @var Quote
     */
    protected $cart;

    /**
     * CartService constructor.
     *
     * @param  CartContract  $cart
     */
    public function __construct(CartContract $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Add a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($customer);

        return $this->cart->addProduct($product, $quantity);
    }

    /**
     * Update a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($customer);

        return $this->cart->updateProduct($product, $quantity);
    }

    /**
     * Delete a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(CustomerContract $customer, string $sku): bool
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($customer);

        return $this->cart->removeProduct($product);
    }

    /**
     * Get the cart item count.
     *
     * @param  CustomerContract  $customer
     * @return int
     */
    public function getItemCount(CustomerContract $customer): int
    {
        $this->cart->loadForCustomer($customer);

        return $this->cart->count();
    }

    /**
     * Get the delivery address of the cart.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDeliveryAddress(CustomerContract $customer): ?AddressContract
    {
        $this->cart->loadForCustomer($customer);

        return $this->cart->getAddress();
    }

    /**
     * Set the delivery address for the cart.
     *
     * @param  CustomerContract  $customer
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(CustomerContract $customer, AddressContract $address): bool
    {
        $this->cart->loadForCustomer($customer);
        $this->cart->setAddress($address);

        return $this->cart->save();
    }

    /**
     * Find a product.
     *
     * @param  string  $sku
     * @return null|ProductContract
     */
    protected function findProduct(string $sku): ?ProductContract
    {
        return app()->make(ProductContract::class)->findBySku($sku);
    }
}