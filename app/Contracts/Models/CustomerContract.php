<?php

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

/**
 * Customer contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CustomerContract
{
    /**
     * Get or set the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;

    /**
     * Get the contact.
     *
     * @return ContactContract
     */
    public function getContact(): ContactContract;

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract;

    /**
     * Get the favorites.
     *
     * @return Collection
     */
    public function getFavorites(): Collection;

    /**
     * Check if the customer has set the product as favorite.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasFavorite(ProductContract $product): bool;

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function addFavorite(ProductContract $product): void;

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function removeFavorite(ProductContract $product): void;
}