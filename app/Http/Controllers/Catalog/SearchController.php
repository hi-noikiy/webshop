<?php

namespace WTG\Http\Controllers\Catalog;

use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Models\Product;
use WTG\Services\SearchService;

/**
 * Search controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchController extends Controller
{
    /**
     * Search page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function getAction(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $searchQuery = $request->input('query');

        if (! $searchQuery) {
            return back();
        }

        /** @var SearchService $service */
        $service = app()->make(SearchService::class);
        $results = $service->searchProducts([
            'query'     => $searchQuery,
            'brand'     => $request->input('brand'),
            'series'     => $request->input('series'),
            'type'     => $request->input('type'),
        ], ($page > 0 ? $page : 1));

        return view('pages.catalog.search', compact('results'));
    }
}