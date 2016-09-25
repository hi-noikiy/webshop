<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order.
 *
 * @property-read \App\User $user
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $products
 * @property string $User_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $comment
 * @property int $addressId
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereProducts($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereAddressId($value)
 */
class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The guarded columns in the table.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the address that the ordered products were sent to.
     *
     * @return \stdClass
     */
    public function getAddress()
    {
        if ($this->addressId === -2) { // This will be returned if the customer came to us to get their order
            $address = new \stdClass();

            $address->name = '';
            $address->street = 'Wordt gehaald';
            $address->postcode = '';
            $address->city = '';
            $address->telephone = '';
            $address->mobile = '';
        } elseif ($this->addressId === -1) { // This will be returned if the order was made before the address column was added
            $customer = User::where('login', $this->User_id)->first();
            $address = new \stdClass();

            $address->name = $customer->company;
            $address->street = $customer->street;
            $address->postcode = $customer->postcode;
            $address->city = $customer->city;
            $address->telephone = 'Unknown';
            $address->mobile = 'Unknown';
        } else { // This will get the shipping address
            $address = Address::find($this->addressId);
        }

        return $address;
    }

    /**
     * Get the user who placed the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'User_id', 'login');
    }
}
