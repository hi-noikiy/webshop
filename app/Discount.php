<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'discounts';

    /**
     * The guarded columns in the table
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function __construct()
    {
        $this->where('group_desc', '!=', 'Vervallen');
    }

    public function user()
	{
		return $this->belongsTo('App\User', 'User_id', 'login');
	}
}
