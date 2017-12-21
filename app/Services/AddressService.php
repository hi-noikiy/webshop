<?php

namespace WTG\Services;

use WTG\Models\Address;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\AddressServiceContract;
use WTG\Http\Requests\Account\Address\CreateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Address service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressService implements AddressServiceContract
{
    /**
     * Get all available addresses for a customer;
     *
     * @param  CustomerContract  $customer
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer): Collection
    {
        $company = $customer->getCompany();

        return $company->getAddresses();
    }

    /**
     * Get an address for a customer by id.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, string $addressId): ?AddressContract
    {
        $addresses = $this->getAddressesForCustomer($customer);
        /** @var AddressContract $address */
        $address = $addresses->firstWhere('id', $addressId);

        return $address;
    }

    /**
     * Create a new address from a request.
     *
     * @param  CreateRequest  $request
     * @return bool
     */
    public function createFromRequest(CreateRequest $request): bool
    {
        $data = $request->only([
            'name', 'address', 'postcode', 'city', 'phone', 'mobile'
        ]);

        $data['company_id'] = $request->user()->getAttribute('company_id');

        $address = new Address($data);

        return $address->save();
    }

    /**
     * Delete an address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function deleteForCustomer(CustomerContract $customer, string $addressId): bool
    {
        /** @var Address $address */
        $address = app()->make(AddressContract::class)
            ->where('customer_id', $customer->identifier())
            ->where('id', $addressId)
            ->firstOrFail();

        return $address->delete();
    }

    /**
     * Set the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, string $addressId): bool
    {
        $contact = $customer->getContact();
        $address = $contact->defaultAddress($addressId);

        if ($address && $address->identifier() === $addressId) {
            return true;
        }

        return false;
    }

    /**
     * Get the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract
    {
        return $customer->getContact()->defaultAddress();
    }

    /**
     * Get the default address id for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|string
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?string
    {
        $defaultAddress = $this->getDefaultAddressForCustomer($customer);

        return $defaultAddress ? $defaultAddress->identifier() : null;
    }
}