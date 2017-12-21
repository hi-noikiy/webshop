<?php

namespace WTG\Contracts\Models;

/**
 * Favorite contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface FavoriteContract
{
    /**
     * Get or set the product identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;
}