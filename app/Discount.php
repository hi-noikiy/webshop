<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 * @package App
 */
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

    /**
     * Discount constructor.
     */
    public function __construct()
    {
        $this->where('group_desc', '!=', 'Vervallen');
    }

    /**
     * Get the user the discount belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo('App\User', 'User_id', 'login');
	}
}
