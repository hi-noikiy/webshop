<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The users that belong to the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company', 'login');
    }

}
