<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DiscountfileController
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountfileController extends Controller
{
    /**
     * Generator page
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('customer.discountfile.index');
    }

    /**
     * Generate the file
     *
     * @param  Request  $request
     * @param  string  $type
     * @param  string  $method
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generate(Request $request, $type, $method)
    {
        if ($type === 'icc') {
            $contents = app('discount_file')->icc();
            $filename = 'icc_data'.Auth::user()->company_id.'.txt';
        } elseif ($type === 'csv') {
            $contents = app('discount_file')->csv();
            $filename = 'icc_data'.Auth::user()->company_id.'.csv';
        } else {
            return redirect('account/discountfile')
                ->withErrors('Ongeldig bestands type.');
        }

        $filePath = storage_path('export/discounts/'.$filename);

        if (\File::exists($filePath)) {
            \File::delete($filePath);
        }

        \File::put($filePath, $contents);

        if ($method === 'download') {
            return \Response::download($filePath);
        } elseif ($method === 'mail') {
            \Mail::send('email.discountfile', [], function ($message) use ($filePath, $filename) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');
                $message->to(Auth::user()->email);
                $message->subject('[WTG Webshop] Kortingsbestand');
                $message->attach($filePath, ['as' => $filename]);
            });

            return redirect()
                ->back()
                ->with('status', 'Het kortingsbestand is verzonden naar '.Auth::user()->email);
        } else {
            return redirect()
                ->back()
                ->withErrors('Geen verzendmethode opgegeven.');
        }
    }
}