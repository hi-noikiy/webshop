<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @package App
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name
 * @property integer $number
 * @property integer $group
 * @property string $altNumber
 * @property string $stockCode
 * @property string $registered_per
 * @property string $packed_per
 * @property string $price_per
 * @property string $refactor
 * @property string $supplier
 * @property string $ean
 * @property string $image
 * @property string $length
 * @property string $price
 * @property integer $vat
 * @property string $brand
 * @property string $series
 * @property string $type
 * @property float $special_price
 * @property string $action_type
 * @property string $keywords
 * @property string $related_products
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $catalog_group
 * @property string $catalog_index
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereGroup($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereAltNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereStockCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereRegisteredPer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product wherePackedPer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product wherePricePer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereRefactor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereSupplier($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereEan($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereLength($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereVat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereSeries($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereSpecialPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereActionType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereRelatedProducts($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCatalogGroup($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCatalogIndex($value)
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
//	public function discount()
//	{
//		$discount = Discount::select([DB::raw('MAX(discount) as value')])->where('User_id', \Auth::user()->login)->where(function ($query) {
//			$query->whereProduct($this->number);
//			$query->orWhere('product', $this->group);
//		})->first();
//
//		return (int) $discount->value;
//	}

    /**
     * Calculate the real price
     *
     * @return string
     */
	public function getRealPriceAttribute()
    {
        if ($this->isAction()) {
            return (double) number_format($this->special_price, 2, ".", "");
        } else {
            return (double) number_format((preg_replace("/\,/", ".", $this->price) * $this->refactor) / $this->price_per, 2, ".", "");
        }
    }

    /**
     * @return string
     */
    public function getPricePerStrAttribute()
    {
        return ($this->refactor == 1 ? Helper::price_per($this->registered_per) : Helper::price_per($this->packed_per));
    }

    /**
     * @return bool
     */
    public function isAction()
    {
        return $this->special_price !== '0.00';
    }

    /**
     * @return int|string
     */
    public function getDiscountAttribute()
    {
        return $this->isAction() ? 0 : Helper::getProductDiscount(\Auth::user()->company_id, $this->group, $this->number);
    }
}
