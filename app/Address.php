<?php namespace App;

use Illuminate\Database\Eloquent\Model;

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

	public function user()
	{
		return $this->belongsTo('App\User', 'User_id', 'login');
	}
}
