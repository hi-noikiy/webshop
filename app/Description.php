<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'product_id'
    ];

    /**
     * Product the description belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'number', 'product_id');
    }
}
