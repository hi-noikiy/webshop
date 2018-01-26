<?php

namespace WTG\Contracts\Models;

/**
 * Product contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ProductContract
{
    /**
     * Get or set the product identifier.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Set the product sku.
     *
     * @param  string  $sku
     * @return ProductContract
     */
    public function setSku(string $sku): ProductContract;

    /**
     * Get the product sku.
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set the product group.
     *
     * @param  string  $group
     * @return ProductContract
     */
    public function setGroup(string $group): ProductContract;

    /**
     * Get the product group.
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Get or set the product name.
     *
     * @param  string  $name
     * @return ProductContract
     */
    public function setName(string $name): ProductContract;

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the product sales unit.
     *
     * @param  string  $salesUnit
     * @return ProductContract
     */
    public function setSalesUnit(string $salesUnit): ProductContract;

    /**
     * Get the product sales unit.
     *
     * @return string
     */
    public function getSalesUnit(): string;
}