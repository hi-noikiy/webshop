<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Customer model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Customer extends Authenticatable implements CustomerContract
{
    use SoftDeletes, HasRoles;

    const CUSTOMER_ROLE_SUPER_ADMIN = 'super-admin';
    const CUSTOMER_ROLE_ADMIN = 'admin';
    const CUSTOMER_ROLE_MANAGER = 'manager';
    const CUSTOMER_ROLE_USER = 'user';

    /**
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * @var array
     */
    protected $guarded = [
        'id',
        'password',
        'remember_token',
        'active'
    ];

    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Contact relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * Favorites relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * Quote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    /**
     * Related quote items through the quote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function quoteItems()
    {
        return $this->hasManyThrough(QuoteItem::class, Quote::class);
    }

    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the contact.
     *
     * @return ContactContract
     */
    public function getContact(): ContactContract
    {
        $contact = $this->getAttribute('contact');

        if (! $contact) {
            /** @var Contact $contact */
            $contact = app()->make(ContactContract::class);
            $contact->setAttribute('customer_id', $this->identifier());
            $contact->save();
        }

        return $contact;
    }

    /**
     * Set the username.
     *
     * @param  string  $username
     * @return CustomerContract
     */
    public function setUsername(string $username): CustomerContract
    {
        return $this->setAttribute('username', $username);
    }

    /**
     * Get the username.
     *
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->getAttribute('username');
    }

    /**
     * Set the active.
     *
     * @param  bool  $active
     * @return CustomerContract
     */
    public function setActive(bool $active): CustomerContract
    {
        return $this->setAttribute('active', $active);
    }

    /**
     * Get the active.
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool) $this->getAttribute('active');
    }

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract
    {
        return $this->getAttribute('company');
    }

    /**
     * Get the favorites.
     *
     * @return Collection
     */
    public function getFavorites(): Collection
    {
        return $this->getAttribute('favorites');
    }

    /**
     * Check if the customer has set the product as favorite.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasFavorite(ProductContract $product): bool
    {
        return $this->favorites()->where('product_id', $product->identifier())->exists();
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function addFavorite(ProductContract $product): void
    {
        $this->favorites()->attach($product->identifier());
    }

    /**
     * Add a product as favorite.
     *
     * @param  ProductContract  $product
     * @return void
     */
    public function removeFavorite(ProductContract $product): void
    {
        $this->favorites()->detach($product->identifier());
    }
}