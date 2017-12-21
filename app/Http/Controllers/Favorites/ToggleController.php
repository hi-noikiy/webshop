<?php

namespace WTG\Http\Controllers\Favorites;

use WTG\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\CustomerContract;

/**
 * Favorites toggle controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Favorites
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ToggleController extends Controller
{
    /**
     * Add or remove a product from the favorites.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(Request $request): JsonResponse
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

        if ($isFavorite) {
            $added = false;
            $customer->removeFavorite($product);
        } else {
            $added = true;
            $customer->addFavorite($product);
        }

        return response()->json([
            'added' => $added,
            'buttonText' => $added ? __('Verwijderen') : __('Toevoegen'),
            'notificationText' => $added ?
                __('Het product is toegevoegd aan uw favorieten.') :
                __('Het product is verwijderd uit uw favorieten.'),
            'success' => true,
            'code' => 200
        ]);
    }
}