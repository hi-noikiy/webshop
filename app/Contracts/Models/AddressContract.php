<?php

namespace WTG\Contracts\Models;

/**
 * Address contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
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

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract;

    /**
     * Get or set the name.
     *
     * @param  null|string  $name
     * @return null|string
     */
    public function name(?string $name = null): ?string;

    /**
     * Get or set the street.
     *
     * @param  null|string  $street
     * @return null|string
     */
    public function street(?string $street = null): ?string;

    /**
     * Get or set the postcode.
     *
     * @param  null|string  $postcode
     * @return null|string
     */
    public function postcode(?string $postcode = null): ?string;

    /**
     * Get or set the city.
     *
     * @param  null|string  $city
     * @return null|string
     */
    public function city(?string $city = null): ?string;

    /**
     * Get or set the phone.
     *
     * @param  null|string  $phone
     * @return null|string
     */
    public function phone(?string $phone = null): ?string;

    /**
     * Get or set the mobile.
     *
     * @param  null|string  $mobile
     * @return null|string
     */
    public function mobile(?string $mobile = null): ?string;
}