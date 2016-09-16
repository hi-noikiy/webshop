<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @package App
 * @property-read \App\User $user
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name
 * @property string $street
 * @property string $city
 * @property string $postcode
 * @property string $telephone
 * @property string $mobile
 * @property integer $User_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address wherePostcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereTelephone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Address whereUpdatedAt($value)
 */
class Address extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'addresses';

	/**
	 * The column that can't be filled
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
     * The user the address belongs to
     *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'User_id', 'login');
	}

    /**
     * @return string
     */
	public function getAddressLineAttribute()
    {
        return $this->name . ", " . $this->street . ", " . $this->postcode . ", " . $this->city;
    }
}
