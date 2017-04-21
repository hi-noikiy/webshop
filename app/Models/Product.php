<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Product extends Model
{
    /**
     * The guarded columns in the table.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Detailed user defined product description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function description()
    {
        return $this->hasOne(Description::class, 'product_id', 'number');
    }

    /**
     * @param $number
     *
     * @param  bool  $fail
     * @return Product|null
     */
    public static function findByNumber($number, $fail = false)
    {
        $query = static::where('number', $number);

        if ($fail) {
            return $query->firstOrFail();
        } else {
            return $query->first();
        }
    }

    /**
     * Price per str field mutator
     *
     * @deprecated
     * @return string
     */
    public function getPricePerStrAttribute()
    {
        return $this->attributes['refactor'] === 1 ?
            app('helper')->price_per($this->attributes['registered_per']) :
            app('helper')->price_per($this->attributes['packed_per']);
    }

    /**
     * Check if the product is a pack.
     *
     * @return bool
     */
    public function isPack()
    {
        return Pack::where('product_number', $this->attributes['number'])->count() === 1;
    }

    /**
     * Check if this product is an action product
     *
     * @return bool
     */
    public function isAction()
    {
        return $this->attributes['special_price'] !== '0.00';
    }

    /**
     * Get the product discount
     *
     * @return float
     */
    public function getDiscount()
    {
        if (!\Auth::check()) {
            return 0.00;
        }

        return $this->isAction() ?
            0.00 :
            app('helper')->getProductDiscount(
                \Auth::user()->company_id,
                $this->attributes['group'],
                $this->attributes['number']
            );
    }

    /**
     * Get the unit price
     *
     * @return float
     */
    public function getPricePerUnit()
    {
        return (float) (preg_replace("/\,/", '.', $this->attributes['price']) * $this->attributes['refactor']) / $this->attributes['price_per'];
    }

    /**
     * Get the calculated formatted price
     *
     * @param  bool  $withDiscount
     * @param  float  $quantity
     * @return string
     */
    public function getPrice(bool $withDiscount, float $quantity = 1.00)
    {
        if ($this->isAction()) {
            return app('format')->price($this->attributes['special_price']);
        } else {
            $price = $this->getPricePerUnit();

            if ($withDiscount) {
                $price = $price - ($price * ($this->getDiscount() / 100));
            }

            return $price * $quantity;
        }
    }

    /**
     * Get the price per string
     *
     * @return string
     */
    public function getPricePer()
    {
        return $this->attributes['refactor'] === 1 ?
            app('helper')->price_per($this->attributes['registered_per']) :
            app('helper')->price_per($this->attributes['packed_per']);
    }

    /**
     * Get the product id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->attributes['id'];
    }
}
