<?php

namespace WTG\Http\Controllers\Catalog;

use WTG\Models\Product;
use WTG\Http\Controllers\Controller;

/**
 * Assortment controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AssortmentController extends Controller
{
    /**
     * Assortment page.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        $query = Product::query();

        $results = collect([
            'products' => $query->paginate(10),
            'brands' => (clone $query)->orderBy('brand')->distinct()->pluck('brand'),
            'series' => (clone $query)->orderBy('series')->distinct()->pluck('series'),
            'types' => (clone $query)->orderBy('type')->distinct()->pluck('type'),
        ]);

        return view('pages.catalog.assortment', compact('results'));
    }
}