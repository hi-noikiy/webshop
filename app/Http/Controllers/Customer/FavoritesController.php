<?php

namespace App\Http\Controllers\Customer;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;
use WTG\Catalog\Interfaces\ProductInterface;
use WTG\Favorites\Interfaces\FavoriteInterface;

/**
 * Class FavoritesController
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class FavoritesController extends Controller
{
    /**
     * List of favorites
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $favorites = [];

        app()->make(FavoriteInterface::class)
            ->customer(Auth::id())
            ->get()
            ->each(function ($favorite) use (&$favorites) {
                /** @var FavoriteInterface $favorite */
                /** @var ProductInterface $product */
                $product = app()->make(ProductInterface::class)
                    ->find($favorite->getProductId());

                $favorites[$product->getSeries()][] = $product;
            });

        $favorites = collect($favorites);

        return view('customer.favorites.index', compact('favorites'));
    }

    /**
     * Check if a product is in the users favorites
     *
     * @param  Request  $request
     * @param  string  $productId
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request, string $productId)
    {
        /** @var ProductInterface $product */
        $product = app()->make(ProductInterface::class)->find($productId);

        if ($product === null) {
            return response([
                'success' => false,
                'message' => trans('catalog::product.not_found', ['product' => $productId])
            ], 400);
        }

        $favorite = app()->make(FavoriteInterface::class)
            ->customer(Auth::id())
            ->get()
            ->first(function ($favorite) use ($product) {
                /** @var FavoriteInterface $favorite */
                return $favorite->getProductId() === $product->getId();
            });

        if ($favorite === null) {
            return response([
                'success' => true,
                'toggle_url' => route('customer::account.favorites::add', ['product' => $product->getId()]),
                'text' => trans('favorites::button.add')
            ]);
        }

        return response([
            'success' => true,
            'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favorite->getId()]),
            'text' => trans('favorites::button.delete')
        ]);
    }

    /**
     * Add a product to the favorites
     *
     * @param  string  $productId
     * @return \Illuminate\Http\Response
     */
    public function add(string $productId)
    {
        /** @var ProductInterface $product */
        $product = app()->make(ProductInterface::class)->find($productId);

        if ($product === null) {
            return response([
                'success' => false,
                'message' => trans('catalog::product.not_found', ['product' => $productId])
            ], 400);
        }

        /** @var FavoriteInterface $favorite */
        $favorite = app()->make(FavoriteInterface::class)
            ->add(
                Auth::id(),
                $product->getId()
            );

        if ($favorite) {
            return response([
                'success' => true,
                'message' => trans('favorites::favorite.added'),
                'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favorite->getId()]),
                'text' => trans('favorites::button.delete')
            ]);
        }

        return response([
            'success' => false,
            'message' => trans('favorites::favorite.already_added')
        ], 400);
    }

    /**
     * Remove a product from the users favorites
     *
     * @param  string  $favoriteId
     * @return \Illuminate\Http\Response
     */
    public function delete(string $favoriteId)
    {
        /** @var FavoriteInterface $favorite */
        $favorite = app()->make(FavoriteInterface::class)
            ->customer(Auth::id())
            ->find($favoriteId);

        if ($favorite === null) {
            return response([
                'success' => false,
                'message' => trans('favorites::favorite.not_found'),
                'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favoriteId]),
                'text' => trans('favorites::favorite.delete'),
            ]);
        }

        $productId = $favorite->getProductId();
        $favorite->delete();

        return response([
            'success' => true,
            'message' => trans('favorites::favorite.deleted'),
            'toggle_url' => route('customer::account.favorites::add', ['product' => $productId]),
            'text' => trans('favorites::button.add')
        ]);
    }
}