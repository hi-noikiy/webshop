<?php

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Http\Requests\Account\Address\CreateRequest;

/**
 * Address service contract.
 *
 * @package     WTG
 * @subpackage  Services\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AddressServiceContract
{
    /**
     * Get all available addresses for a customer.
     *
     * @param  CustomerContract  $customer
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer): Collection;

    /**
     * Get an address for a customer by id.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, string $addressId): ?AddressContract;

    /**
     * Create a new address from a request.
     *
     * @param  CreateRequest  $request
     * @return bool
     */
    public function createFromRequest(CreateRequest $request): bool;

    /**
     * Delete an address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function deleteForCustomer(CustomerContract $customer, string $addressId): bool;

    /**
     * Set the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, string $addressId): bool;

    /**
     * Get the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract;

    /**
     * Get the default address id for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|string
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?string;
}