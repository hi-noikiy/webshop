<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\CustomerContract;

/**
 * Contact model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Contact extends Model implements ContactContract
{
    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class)
            ->where('company_id', $this->getCustomer()->getCompany()->identifier());
    }

    /**
     * Get the identifier
     *
     * @param null|string $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getKey();
    }

    /**
     * Get the customer.
     *
     * @return CustomerContract
     */
    public function getCustomer(): CustomerContract
    {
        return $this->getAttribute('customer');
    }

    /**
     * Get or set the contact email.
     *
     * @param  null|string  $email
     * @return null|string
     */
    public function contactEmail(?string $email = null): ?string
    {
        if ($email) {
            $this->setAttribute('contact_email', $email);
        }

        return $this->getAttribute('contact_email');
    }

    /**
     * Get or set the order email.
     *
     * @param  null|string  $email
     * @return null|string
     */
    public function orderEmail(?string $email = null): ?string
    {
        if ($email) {
            $this->setAttribute('order_email', $email);
        }

        return $this->getAttribute('order_email');
    }

    /**
     * Get or set the default address.
     *
     * @param  null|string  $addressId
     * @return null|AddressContract
     */
    public function defaultAddress(?string $addressId = null): ?AddressContract
    {
        if ($addressId) {
            $this->address()->associate($addressId);
        }

        return $this->getAttribute('address');
    }
}