<?php

namespace WTG\Http\Controllers\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;

/**
 * Dashboard controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * The account dashboard.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $address = $customer->getContact()->defaultAddress();

        return view('pages.account.dashboard', compact('customer', 'address'));
    }
}