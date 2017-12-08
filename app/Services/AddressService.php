<?php

namespace WTG\Services;

use WTG\Models\Address;
use WTG\Services\Contracts\AddressServiceContract;

/**
 * Address service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressService implements AddressServiceContract
{
    /**
     * Create a new address.
     *
     * @param  array  $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $address = new Address($data);

        return $address->save();
    }
}