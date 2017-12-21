<?php

namespace WTG\Http\Controllers\Favorites;

use WTG\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\CustomerContract;

/**
 * Favorites check controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Favorites
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckController extends Controller
{
    /**
     * Check if a product is in the favorites.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $product = Product::findBySku($sku);

        if (! $product) {
            return response()->json([
                'message' => __('Geen product gevonden voor sku :sku', ['sku' => $sku])
            ], 400);
        }

        $isFavorite = $customer->hasFavorite($product);

        return response()->json([
            'isFavorite' => $isFavorite,
            'buttonText' => $isFavorite ? __('Verwijderen') : __('Toevoegen'),
            'success' => true,
            'code' => 200
        ]);
    }
}