<?php

namespace WTG\Http\Controllers\Checkout;

use WTG\Models\Address;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Contracts\CartContract;
use WTG\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use WTG\Http\Requests\UpdateQuoteAddressRequest;

/**
 * Address controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressController extends Controller
{
    /**
     * Address selection page.
     *
     * @param  Request  $request
     * @param  CartContract  $cart
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request, CartContract $cart)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        /** @var Collection $addresses */
        $addresses = $customer->getAttribute('company')->getAttribute('addresses');

        $cart->loadForCustomer($customer);
        /** @var Address $quoteAddress */
        $quoteAddress = $cart->getAddress();

        return view('pages.checkout.address', compact('customer', 'addresses', 'quoteAddress'));
    }

    /**
     * Change the delivery address of the quote.
     *
     * @param  UpdateQuoteAddressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(UpdateQuoteAddressRequest $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        /** @var Address $address */
        $address = $customer
            ->getAttribute('company')
            ->addresses()
            ->where('id', $request->input('addressId'))
            ->first();

        if (!$address) {
            return response()->json([
                'error' => __("Het opgegeven adres is niet gevonden.")
            ], 400);
        }

        $quote = $customer->getActiveQuote();
        $quote->setAttribute('address_id', $address->getAttribute('id'));
        $quote->save();

        return response()->json([
            'message' => __("Het adres is aangepast.")
        ]);
    }
}