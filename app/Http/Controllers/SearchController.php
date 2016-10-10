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
     * @param  int  $page
     * @return \Illuminate\View\View
     */
    public function search(Request $request, $page = 1)
    {
        return view('webshop.search');
    }

    public function filter(Request $request)
    {
        $brands = [];
        $series = [];
        $types = [];

        $str = $request->get('q');
        $page = (request('page') ?? 1);
        $perPage = (request('perPage') ?? 10);

        $search = Search::text($str, $page, $perPage);

        $productCollection = $search['products'];
        $totalHits = $search['total'];
        $hash = '#q=' . request('q');

        if (request('brand')) {
            $hash .= '&brand=' . request('brand');
        }
        if (request('serie')) {
            $hash .= '&serie=' . request('serie');
        }
        if (request('type')) {
            $hash .= '&type=' . request('type');
        }
        if (request('perPage')) {
            $hash .= '&perPage=' . request('perPage');
        }

        foreach ($productCollection as $item) {
            $brands[] = $item->brand;
            $series[] = $item->series;
            $types[] = $item->type;
        }

        $paginator = (new LengthAwarePaginator($productCollection, $totalHits, $perPage, $page, [
            'path' => '/search' . $hash
        ]));

        $resultsHtml = view('webshop.partials.results', [
            'paginator'  => $paginator,
            'products'   => $productCollection->forPage($page, $perPage),
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4),
        ])->render();

        $brandsHtml = view('webshop.partials.filters.brands', [
            'brands' => collect($brands)->unique()->sort()
        ])->render();

        $seriesHtml = view('webshop.partials.filters.series', [
            'series' => collect($series)->unique()->sort()
        ])->render();

        $typesHtml = view('webshop.partials.filters.types', [
            'types' => collect($types)->unique()->sort()
        ])->render();

        // Return the search view with the fetched data
        return \Response::json([
            'results' => $resultsHtml,
            'brands'  => $brandsHtml,
            'series'  => $seriesHtml,
            'types'   => $typesHtml
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
