<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\Elastic\ProductSearch;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SearchController.
 *
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
        $page = $request->input('page');

        $terms = [];
        $terms['brand'] = $request->input('brand');
        $terms['series'] = $request->input('serie');
        $terms['type'] = $request->input('type');

        $search = new ProductSearch($request->input('q'), $terms);
        $products = $search->getItems();
        $totalItems = $search->getTotal();
        $filters = $search->getFilters();
        $suggestions = $search->getSuggestions();

        $paginator = new LengthAwarePaginator(
            $products->forPage($page, 15),
            $totalItems,
            15,
            $page
        );
        $paginator->setPath('search');

        // Return the search view with the fetched data
        return view('webshop.search', [
            'paginator'  => $paginator,
            'suggestions' => $suggestions,
            'filters'    => $filters,
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
