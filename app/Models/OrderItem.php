<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Order item model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OrderItem extends Model
{
    /**
     * Order relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}