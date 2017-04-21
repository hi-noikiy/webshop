<?php

namespace App\Http\Controllers\Customer;

use WTG\Address\Interfaces\AddressInterface;
use WTG\Address\Models\Address;
use Illuminate\Support\Facades\Auth;

/**
 * Address controller
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AddressController extends Controller
{
    /**
     * The address list
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $addresses = app()->make(AddressInterface::class)
            ->customer(Auth::id())
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }
}