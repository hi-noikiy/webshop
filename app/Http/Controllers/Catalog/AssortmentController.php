<?php

namespace App\Http\Controllers\Catalog;

use Illuminate\Http\Request;
use WTG\Catalog\Models\Product;
use App\Http\Controllers\Controller;

/**
 * Assortment controller
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AssortmentController extends Controller
{
    const URL_PARAM_TYPE = 'type';
    const URL_PARAM_BRAND = 'brand';
    const URL_PARAM_SERIES = 'series';

    /**
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function view(Request $request)
    {
        $query = Product::whereRaw('1 = 1');
        $series = $types = collect();
        $brands = Product::getBrandCollection();

        if ($request->has(static::URL_PARAM_BRAND)) {
            $query->brand($request->input(static::URL_PARAM_BRAND));
            $series = Product::getSeriesCollection(
                $request->input(static::URL_PARAM_BRAND)
            );

            if ($request->has(static::URL_PARAM_SERIES)) {
                $query->series($request->input(static::URL_PARAM_SERIES));
                $types = Product::getTypesCollection(
                    $request->input(static::URL_PARAM_BRAND),
                    $request->input(static::URL_PARAM_SERIES)
                );

                if ($request->has(static::URL_PARAM_TYPE)) {
                    $query->type($request->input(static::URL_PARAM_TYPE));
                }
            }
        }

        $products = $query->paginate(20);

        return view('catalog.assortment', compact('products', 'brands', 'series', 'types'));
    }
}