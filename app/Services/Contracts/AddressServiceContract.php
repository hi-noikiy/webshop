<?php

namespace WTG\Services\Contracts;

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
     * Create a new address.
     *
     * @param  array  $data
     * @return bool
     */
    public function create(array $data): bool;
}