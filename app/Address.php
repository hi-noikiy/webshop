<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * @package App
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
}
