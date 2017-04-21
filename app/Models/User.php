<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use WTG\Checkout\Models\Quote;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Non mass-assignable fields.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'isAdmin', 'manager', 'cart', 'favorites'];

    /**
     * Get the list of addresses that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'User_id', 'company_id');
    }

    /**
     * Get the user's discounts.
     *
     * @param string|null $type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discounts($type = null)
    {
        $query = $this->hasMany(Discount::class, 'User_id', 'company_id');
        $query->where('group_desc', '!=', 'Vervallen');

        switch ($type) {
            case 'group':
                $query->where('table', 'VA-220');
                break;

            case 'product':
                $query->where('table', 'VA-260');
                break;

            case 'product-default':
                $query->where('table', 'VA-261');
                break;

            case 'group-default':
                $query->where('table', 'VA-221');
                break;
        }

        return $query;
    }

    /**
     * Get the users orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'User_id', 'login');
    }

    /**
     * The company the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'login');
    }

    /**
     * Get the sub accounts that have the same parent company as
     * the current account.
     *
     * @param  int  $paginate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subAccounts($paginate = 15)
    {
        return self::where('company_id', $this->company_id)->paginate($paginate);
    }

    /**
     * Check if this is the main account.
     *
     * @return bool
     */
    public function isMain()
    {
        return $this->company_id === $this->username;
    }

    /**
     * The favorites of the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the quote that belongs to the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function getQuote()
    {
        $quote = $this->quote;

        if ($quote === null) {
            $quote = new Quote;

            $this->quote()->save($quote);
        }

        return $quote;
    }
}
