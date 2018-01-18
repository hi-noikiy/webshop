<?php

namespace WTG\Services;

use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\FavoritesServiceContract;

/**
 * Favorites service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesService implements FavoritesServiceContract
{
    /**
     * Add a list of favorites to the cart.
     *
     * @param  CustomerContract  $customer
     * @param  array  $productIds
     * @return void
     */
    public function addFavoritesToCart(CustomerContract $customer, array $productIds)
    {
        foreach ($productIds as $productId) {
            //
        }
    }
}