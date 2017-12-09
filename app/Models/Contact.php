<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Contact model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Contact extends Model
{
    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}