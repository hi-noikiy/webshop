<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

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
        $query = Product::where(function ($query) use ($request) {
            if ($request->has('brand')) {
                $query->where('brand', $request->input('brand'));
            }
            if ($request->has('serie')) {
                $query->where('series', $request->input('serie'));
            }
            if ($request->has('type')) {
                $query->where('type', $request->input('type'));
            }
        })->where(function ($query) use ($str, $request) {
            if ($request->has('q')) {
                $query->orWhere('number', 'LIKE', '%'.$str.'%')
                    ->orWhere('group', 'LIKE', '%'.$str.'%')
                    ->orWhere('altNumber', 'LIKE', '%'.$str.'%')
                    ->orWhere('ean', 'LIKE', '%'.$str.'%');

                $query->orWhere(function ($query) use ($request) {
                    foreach (explode(' ', $request->input('q')) as $word) {
                        // Split the input so the order of the search query doesn't matter
                        $query->where(\DB::raw('CONCAT(name, " ", keywords)'), 'LIKE', "%{$word}%");
                    }
                });
            }
        });

        // Get all the results to filter the brands, series and types from it
        $allResults = $query->orderBy('number', 'asc')->get();

        // Get the paginated results
        $results = $query->paginate(25);

        // Initialize $brands, $series, $types as array
        $brands =
        $series =
        $types = [];

        // Get the brands, series and types from the search results
        foreach ($allResults as $product) {
            $brands[] = $product->brand;
            $series[] = $product->series;
            $types[] = $product->type;
        }

        // Sort the arrays (Case Insensitive)
        sort($brands);
        sort($series);
        sort($types);

        // Return the search view with the fetched data
        return view('webshop.search', [
            'results'    => $results,
            'brands'     => array_unique($brands),
            'series'     => array_unique($series),
            'types'      => array_unique($types),
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
