<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * The column that can't be filled.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The user the address belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'User_id', 'login');
    }

    /**
     * @return string
     */
    public function getAddressLineAttribute()
    {
        return $this->name.', '.$this->street.', '.$this->postcode.', '.$this->city;
    }
}
