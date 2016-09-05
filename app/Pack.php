<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pack
 *
 * @package App
 * @property-read \App\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PackProduct[] $products
 * @mixin \Eloquent
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $product_number
 * @method static \Illuminate\Database\Query\Builder|\App\Pack whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pack whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pack whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pack whereProductNumber($value)
 */
class Pack extends Model
{
    /**
     * Guarded columns
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The main product representing this pack
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_number', 'number');
    }

    /**
     * List of products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(PackProduct::class);
    }
}
