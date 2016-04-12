<?php namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use DB;

class SearchController extends Controller {

    /**
     * The search page
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $str    = $request->get('q');
        $query  = DB::table('products');

        $query->where(function ($query) use ($request) {
            if ($request->has('brand')) $query->where('brand', $request->get('brand'));
            if ($request->has('serie')) $query->where('series', $request->get('serie'));
            if ($request->has('type')) $query->where('type', $request->get('type'));
        });

        if ($request->has('q')) {
            $query->where(function ($query) use ($str, $request) {
                $query->orWhere('number', 'LIKE', '%' . $str . '%')
                    ->orWhere('group', 'LIKE', '%' . $str . '%')
                    ->orWhere('altNumber', 'LIKE', '%' . $str . '%')
                    ->orWhere('ean', 'LIKE', '%' . $str . '%');

                $query->orWhere(function ($query) use ($request) {
                    foreach (explode(' ', $request->get('q')) as $word) {
                        // Split the input so the order of the search query doesn't matter
                        $query->where(DB::raw('CONCAT(name, " ", keywords)'), "LIKE", "%{$word}%");
                    }
                });
            });
        }

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
            'results' => $results,
            'brands' => array_unique($brands),
            'series' => array_unique($series),
            'types' => array_unique($types),
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4)
        ]);
    }

    /**
     * Specials
     *
     * @return mixed
     */
    public function specials()
    {
        $products = Product::select(['number', 'name', 'image'])
            ->where('action_type', 'Actie')
            ->orderBy('number', 'asc')
            ->paginate(25);

        // Return the search view with the fetched data
        return view('webshop.specials', [
            'results'    => $products,
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4)
        ]);
    }

    /**
     * A search page only searching for the clearance products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clearance()
    {
        $results = \DB::table('products')
            ->where('action_type', 'Opruiming')
            ->orderBy('number', 'asc')
            ->paginate(25);

        // Return the search view with the fetched data
        return view('webshop.altSearch', [
            'results' => $results,
            'title' => 'Opruiming',
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4)
        ]);
    }

}