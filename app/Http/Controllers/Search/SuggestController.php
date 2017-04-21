<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WTG\Catalog\Models\Product;

class SuggestController extends Controller
{
    public function view(Request $request)
    {
        $query = $request->input('q', null);

        if ($query === null) {
            return response([
                'success' => false,
                'count' => 0
            ]);
        }

        $results = Product::where('sku', 'LIKE', '%'.$query.'%')
            ->orWhere('group', 'LIKE', '%'.$query.'%')
            ->orWhere('alternate_sku', 'LIKE', '%'.$query.'%')
            ->orWhere('ean', 'LIKE', '%'.$query.'%')
            ->orWhere(function ($query) use ($request) {
                foreach (explode(' ', $request->input('q')) as $word) {
                    // Split the input so the order of the search query doesn't matter
                    $query->where(\DB::raw('CONCAT(name, " ", keywords)'), 'LIKE', "%{$word}%");
                }
            })
            ->limit(5)
            ->get();

        // Return the search view with the fetched data
        return response([
            'success' => true,
            'count' => $results->count(),
            'suggestBoxHtml' => view('search.suggest', compact('results'))->render()
        ]);
    }
}