<?php

namespace WTG\Http\Controllers\Checkout;

use WTG\Models\Order;
use WTG\Models\Customer;
use WTG\Models\OrderItem;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;

/**
 * Finish controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FinishController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        $order = session('order');

        if (!$order) {
            return back();
        }

        return view('pages.checkout.finished', compact('order'));
    }

    /**
     * Finish order action.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function postAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $quote = $customer->getActiveQuote();
        $address = $quote->getAddress();

        if ($quote->getItemCount() === 0) {
            return back()->withErrors(__("U kunt geen bestelling afronden met een lege winkelwagen."));
        }

        \DB::beginTransaction();

        try {
            $order = new Order;

            $order->setAttribute('company_id', $customer->getAttribute('company_id'));
            $order->setAttribute('customer_number', $customer->company->getAttribute('customer_number'));
            $order->setAttribute('name', $address->getAttribute('name'));
            $order->setAttribute('street', $address->getAttribute('street'));
            $order->setAttribute('postcode', $address->getAttribute('postcode'));
            $order->setAttribute('city', $address->getAttribute('city'));
            $order->setAttribute('comment', $request->input('comment'));

            $order->save();

            foreach ($quote->items as $item) {
                $orderItem = new OrderItem;

                $orderItem->setAttribute('order_id', $order->getAttribute('id'));
                $orderItem->setAttribute('name', $item->product->getAttribute('name'));
                $orderItem->setAttribute('sku', $item->product->getAttribute('sku'));
                $orderItem->setAttribute('qty', $item->getAttribute('qty'));
                $orderItem->setAttribute('price', $item->product->getPrice(true));
                $orderItem->setAttribute('subtotal', $item->product->getPrice(true, $item->getAttribute('qty')));

                $orderItem->save();
            }

            $quote->delete();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();

            dd($e);

            return back()->withErrors(__("Er is een fout opgetreden tijdens het opslaan van de bestelling."));
        }

        session()->flash('order', $order);

        return redirect()->route('checkout.finished');
    }
}