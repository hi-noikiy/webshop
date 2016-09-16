<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Company.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $login
 * @property string $company
 * @property string $street
 * @property string $postcode
 * @property string $city
 * @property string $email
 * @property bool $active
 * @property bool $isAdmin
 * @property string $password
 * @property string $favorites
 * @property string $cart
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCompany($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereStreet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company wherePostcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereIsAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereFavorites($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereUpdatedAt($value)
 */
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
