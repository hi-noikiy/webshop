<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PackProduct.
 *
 * @property-read \App\Pack $pack
 * @property-read \App\Product $details
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $pack_id
 * @property string $product
 * @property int $amount
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct wherePackId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct whereProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PackProduct whereUpdatedAt($value)
 */
class PackProduct extends Model
{
    /**
     * Return the parent pack.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    /**
     * Product details.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function details()
    {
        return $this->belongsTo(Product::class, 'product', 'number');
    }
}
