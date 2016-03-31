<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
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
