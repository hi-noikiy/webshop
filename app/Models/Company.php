<?php

namespace App\Models;

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
        return $this->hasMany(User::class, 'company_id', 'login');
    }

    /**
     * The main user for the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mainUser()
    {
        return $this->hasOne(User::class, 'username', 'login');
    }
}
