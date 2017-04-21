<?php

namespace App\Http\Controllers\Catalog;

use Illuminate\Http\Request;
use WTG\Catalog\Models\Product;
use App\Http\Controllers\Controller;

/**
 * Specials controller
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SpecialsController extends Controller
{
    /**
     * Show the specials
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function view(Request $request)
    {
        $products = Product::action(Product::ACTION_TYPE_SPECIAL)->paginate(20);

        return view('catalog.specials', compact('products'));
    }
}