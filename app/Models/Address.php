<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CompanyContract;

/**
 * Address model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Address extends Model implements AddressContract
{
    /**
     * Related company model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract
    {
        return $this->getAttribute('company');
    }

    /**
     * Get or set the name.
     *
     * @param  null|string  $name
     * @return null|string
     */
    public function name(?string $name = null): ?string
    {
        if ($name) {
            $this->setAttribute('name', $name);
        }

        return $this->getAttribute('name');
    }

    /**
     * Get or set the street.
     *
     * @param  null|string  $street
     * @return null|string
     */
    public function street(?string $street = null): ?string
    {
        if ($street) {
            $this->setAttribute('street', $street);
        }

        return $this->getAttribute('street');
    }

    /**
     * Get or set the postcode.
     *
     * @param  null|string  $postcode
     * @return null|string
     */
    public function postcode(?string $postcode = null): ?string
    {
        if ($postcode) {
            $this->setAttribute('postcode', $postcode);
        }

        return $this->getAttribute('postcode');
    }

    /**
     * Get or set the city.
     *
     * @param  null|string  $city
     * @return null|string
     */
    public function city(?string $city = null): ?string
    {
        if ($city) {
            $this->setAttribute('city', $city);
        }

        return $this->getAttribute('city');
    }

    /**
     * Get or set the phone.
     *
     * @param  null|string  $phone
     * @return null|string
     */
    public function phone(?string $phone = null): ?string
    {
        if ($phone) {
            $this->setAttribute('phone', $phone);
        }

        return $this->getAttribute('phone');
    }

    /**
     * Get or set the mobile.
     *
     * @param  null|string  $mobile
     * @return null|string
     */
    public function mobile(?string $mobile = null): ?string
    {
        if ($mobile) {
            $this->setAttribute('mobile', $mobile);
        }

        return $this->getAttribute('mobile');
    }
}