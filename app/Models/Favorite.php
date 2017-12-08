<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Favorite model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Favorite extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Customer relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}