<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Company model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Company extends Model
{
    use SoftDeletes;

    /**
     * Customers relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * @param  Builder  $query
     * @param  string  $customerNumber
     * @return Builder
     */
    public function scopeCustomerNumber(Builder $query, $customerNumber): Builder
    {
        return $query->where('customer_number', $customerNumber);
    }

    /**
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Order relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Change the default address.
     *
     * @param  int|Address  $address
     * @return bool
     */
    public function setDefaultAddress($address): bool
    {
        if (! $address instanceof Address) {
            $address = Address::where('company_id', $this->getAttribute('id'))
                ->where('id', $address)
                ->first();

            if ($address === null) {
                return false;
            }
        }

        if ($address->getAttribute('company_id') !== $this->getAttribute('id')) {
            return false;
        }

        $this->addresses()->where(Address::COLUMN_IS_DEFAULT, true)->update([
            Address::COLUMN_IS_DEFAULT => false
        ]);

        $address->setAttribute(Address::COLUMN_IS_DEFAULT, true);

        return $address->save();
    }
}