<?php namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller {

    /**
     * Specials
     *
     * @return mixed
     */
    public function specials()
    {
        $product = \DB::table('products')
            ->select(['number', 'name', 'image'])
            ->Where('action_type', 'Actie')
            ->orderBy('number', 'asc');

        $query = \DB::table('packs')
            ->select(['id', 'name', 'image'])
            ->union($product);

        // Get all the results
        $results = $query->get();

        // Return the search view with the fetched data
        return view('webshop.specials', [
            'results'    => new LengthAwarePaginator($results, count($results), 25),
            'title'      => 'Acties',
            'scriptTime' => round(microtime(true) - LARAVEL_START, 4)
        ]);
    }

}