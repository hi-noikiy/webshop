<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\AddressContract;

/**
 * Address model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Address extends Model implements AddressContract
{
    const COLUMN_IS_DEFAULT = 'is_default';

    /**
     * Related company model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getKey();
    }
}