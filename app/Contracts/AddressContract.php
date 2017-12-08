<?php

namespace WTG\Contracts;

/**
 * Address contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AddressContract
{
    /**
     * Address identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;
}