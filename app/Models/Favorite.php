<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Favorite
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Favorite extends Model
{
    public $timestamps = false;

    /**
     * The user the model belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The product the model refers to
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'number', 'product_number');
    }
}
