<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 *
 * @package App
 * @property-read \App\User $user
 * @mixin \Eloquent
 * @property integer $id
 * @property string $table
 * @property integer $User_id
 * @property integer $product
 * @property string $start_date
 * @property string $end_date
 * @property string $discount
 * @property string $group_desc
 * @property string $product_desc
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereTable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereGroupDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereProductDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Discount whereUpdatedAt($value)
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
