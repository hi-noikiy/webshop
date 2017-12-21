<?php

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

/**
 * Company contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CompanyContract
{
    /**
     * Get or set the identifier.
     *
     * @param  null|string  $id
     * @return null|string
     */
    public function identifier(?string $id = null): ?string;

    /**
     * Get or set the name.
     *
     * @param  null|string  $name
     * @return null|string
     */
    public function name(?string $name = null): ?string;

    /**
     * Get or set the customer number.
     *
     * @param  null|string  $customerNumber
     * @return null|string
     */
    public function customerNumber(?string $customerNumber = null): ?string;

    /**
     * Get the addresses.
     *
     * @return Collection
     */
    public function getAddresses(): Collection;
}