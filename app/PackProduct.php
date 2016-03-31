<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackProduct extends Model
{
    /**
     * Return the parent pack
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    /**
     * Product details
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function details()
    {
        return $this->belongsTo(Product::class, 'product', 'number');
    }
}
