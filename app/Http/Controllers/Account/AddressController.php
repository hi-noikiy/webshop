<?php

namespace WTG\Http\Controllers\Account;

use WTG\Models\Address;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\CreateAddressRequest;
use WTG\Http\Requests\UpdateDefaultAddressRequest;
use WTG\Services\Contracts\AddressServiceContract as AddressService;

/**
 * Address controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AddressController extends Controller
{
    /**
     * The address list.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addresses = $customer->company->getAttribute('addresses');

        return view('pages.account.addresses', compact('customer', 'addresses'));
    }

    /**
     * Create a new address.
     *
     * @param  CreateAddressRequest  $request
     * @param  AddressService  $addressService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putAction(CreateAddressRequest $request, AddressService $addressService)
    {
        $data = $request->only([
            'name', 'address', 'postcode', 'city', 'phone', 'mobile'
        ]);

        $data['company_id'] = $request->user()->getAttribute('company_id');

        if ($addressService->create($data)) {
            return back()->with('status', __("Het adres is toegevoegd."));
        }

        return back()
            ->withErrors(__("Er is een fout opgetreden tijdens het opslaan van het adres."))
            ->withInput($request->all());
    }

    /**
     * Change the default address.
     *
     * @param  UpdateDefaultAddressRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function patchAction(UpdateDefaultAddressRequest $request)
    {
        $isSuccess = $request->user()->setDefaultAddress($request->input('address-id'));

        if ($isSuccess) {
            return back()->with('status', __("Het standaard adres is aangepast."));
        }

        return back()
            ->withErrors(__("Er is een fout opgetreden tijdens het aanpassen van het adres."));
    }

    /**
     * Remove an address.
     *
     * @param  Request  $request
     * @param  Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, Address $address)
    {
        if ($address->delete()) {
            return back()->with("status", __("Het adres is verwijderd."));
        } else {
            return back()
                ->withErrors(__("Er is een fout opgetreden tijdens het verwijderen van het adres."));
        }
    }
}