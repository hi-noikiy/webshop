<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App
 */
class Product extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	/**
 	 * The guarded columns in the table
 	 *
	 * @var array
	 */
	protected $guarded = ['id'];

    /**
     * Check if the product if a pack
     *
     * @return bool
     */
    public function isPack()
    {
        return Pack::where('product_number', $this->number)->count() === 1;
    }

	/**
	 * Get the discount for a product
	 *
	 * @return int
	 */
	public function discount()
	{
		$discount = Discount::select([DB::raw('MAX(discount) as value')])->where('User_id', \Auth::user()->login)->where(function ($query) {
			$query->whereProduct($this->number)
				->orWhere('product', $this->group);
		})->first();

		return (int) $discount->value;
	}
}
