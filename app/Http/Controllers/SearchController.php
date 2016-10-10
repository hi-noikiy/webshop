<?php

namespace App\Http\Controllers;

use App\Product;
use App\Elastic\Search;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
        $appends = [];
        $brands = [];
        $series = [];
        $types = [];

        $str = $request->get('q');
        $page = (request('page') ?? 1);
        $perPage = (request('perPage') ?? 15);

        $search = Search::text($str, $page, $perPage);

        $productCollection = $search['products'];
        $totalHits = $search['total'];

        if (request('brand')) {
            $appends['brand'] = request('brand');
        }
        if (request('serie')) {
            $appends['serie'] = request('serie');
        }
        if (request('type')) {
            $appends['type'] = request('type');
        }
        if (request('perPage')) {
            $appends['perPage'] = request('perPage');
        }
        $appends['q'] = request('q');

        $paginator = (new LengthAwarePaginator($productCollection, $totalHits, $perPage, $page, [
            'path' => '/search'
        ]))->appends($appends);

        foreach ($productCollection as $item) {
            $brands[] = $item->brand;
            $series[] = $item->series;
            $types[] = $item->type;
        }

        // Return the search view with the fetched data
        return view('webshop.search', [
            'paginator'  => $paginator,
            'products'   => $productCollection->forPage($page, $perPage),
            'brands'     => collect($brands)->unique()->sort(),
            'series'     => collect($series)->unique()->sort(),
            'types'      => collect($types)->unique()->sort(),
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
