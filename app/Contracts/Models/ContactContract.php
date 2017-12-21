<?php

namespace WTG\Contracts\Models;

/**
 * Contact contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ContactContract
{
    /**
     * Get or set the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;

    /**
     * Get the customer.
     *
     * @return CustomerContract
     */
    public function getCustomer(): CustomerContract;

    /**
     * Get or set the contact email.
     *
     * @param  null|string  $email
     * @return null|string
     */
    public function contactEmail(?string $email = null): ?string;

    /**
     * Get or set the order email.
     *
     * @param  null|string  $email
     * @return null|string
     */
    public function orderEmail(?string $email = null): ?string;

    /**
     * Get or set the default address.
     *
     * @param  null|string  $addressId
     * @return null|AddressContract
     */
    public function defaultAddress(?string $addressId = null): ?AddressContract;
}