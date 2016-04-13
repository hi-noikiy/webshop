<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pack
 * @package App
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
