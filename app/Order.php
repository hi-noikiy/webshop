<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';

	/**
 	 * The guarded columns in the table
 	 *
	 * @var array
	 */
	protected $guarded = array('id');

    public function getAddress()
    { 
        if ($this->addressId === -2)
        { // This will be returned if the customer came to us to get their order
            $address = new \stdClass();

            $address->name       = "";
            $address->street     = "Wordt gehaald";
            $address->postcode   = "";
            $address->city       = "";
            $address->telephone  = "";
            $address->mobile     = "";
        } else if ($this->addressId === -1)
        { // This will be returned if the order was made before the address column was added
            $customer = User::where('login', $this->User_id)->first();
            $address = new \stdClass();
            
            $address->name       = $customer->company;
            $address->street     = $customer->street;
            $address->postcode   = $customer->postcode;
            $address->city       = $customer->city;
            $address->telephone  = 'Unknown';
            $address->mobile     = 'Unknown';
        } else
        { // This will get the shipping address
            $address = Address::find($this->addressId);
        }

        return $address;
    }

}
