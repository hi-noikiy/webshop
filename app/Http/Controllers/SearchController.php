<?php

namespace App\Http\Controllers;

use App\Product;
use App\Elastic\Search;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SearchController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SearchController extends Controller
{
    /**
     * The search page.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $str = $request->get('q');

        $search = Search::text($str);

        $productCollection = $search['products'];
        $totalHits = $search['total'];

//        dd($productCollection);

        $results = new LengthAwarePaginator($productCollection, $totalHits, 20, request('page'));

        // Return the search view with the fetched data
        return view('webshop.search', [
            'results'    => $results,
            'brands'     => $productCollection->unique('brand')->values()->sortBy('brand')->pluck('brand'),
            'series'     => $productCollection->unique('series')->values()->sortBy('series')->pluck('series'),
            'types'      => $productCollection->unique('type')->values()->sortBy('type')->pluck('type'),
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4),
        ]);
    }

    /**
     * Specials.
     *
     * @return \Illuminate\View\View
     */
    public function specials()
    {
        $products = Product::select(['number', 'name', 'image', 'special_price'])
            ->where('action_type', 'Actie')
            ->orderBy('number', 'asc')
            ->paginate(25);

        // Return the search view with the fetched data
        return view('webshop.specials', [
            'results'    => $products,
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4),
        ]);
    }

    /**
     * A search page only searching for the clearance products.
     *
     * @return \Illuminate\View\View
     */
    public function clearance()
    {
        $results = Product::where('action_type', 'Opruiming')
            ->orderBy('number', 'asc')
            ->paginate(25);

        // Return the search view with the fetched data
        return view('webshop.clearance', [
            'results'    => $results,
            'title'      => 'Opruiming',
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4),
        ]);
    }
}
