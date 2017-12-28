<?php

namespace App\Http\Controllers;

use Auth;
use Helper;
use Session;
use App\Pack;
use App\Product;
use App\PackProduct;
use Illuminate\Http\Request;
use App\Exceptions\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductController extends Controller
{
    /**
     * The product page
     * Will throw 404 error when no product matches the product id.
     *
     * @param  Request $request
     * @param  int  $product_Id
     * @throws ProductNotFoundException
     * @return \Illuminate\View\View
     */
    public function showProduct(Request $request, $product_Id = null)
    {
        if (strlen($product_Id) === 7) {
            $productMap = \Cache::remember('old-skus', 3600, function () {
                $productMap = [];
                $handle = fopen(storage_path('app/sku-conversion.csv'), 'r');

                while(($data = fgetcsv($handle, 0, ';')) !== false) {
                    $oldSku = $data[0] ?? false;
                    $newSku = $data[1] ?? false;

                    if (!$oldSku || !$newSku) {
                        continue;
                    }

                    $productMap[$oldSku] = (int) $newSku;
                }

                return $productMap;
            });

            if (isset($productMap[$product_Id])) {
                $newSku = $productMap[$product_Id];

                return redirect('product/'.$newSku, Response::HTTP_MOVED_PERMANENTLY);
            } else {
                abort(Response::HTTP_GONE, "Het opgevraagde productnummer is ongeldig of bestaat niet meer.");
            }
        }

        // Store the product id in the session
        Session::flash('product_id', $product_Id);

        try {
            $product = Product::where('number', $product_Id)->firstOrFail();
        } catch (\Exception $e) {
            throw new ProductNotFoundException($product_Id);
        }

        $discount = (Auth::check() ? Helper::getProductDiscount(Auth::user()->company_id, $product->group, $product->number) : null);
        $prevPage = $request->get('ref');
        $related_products = [];
        $pack_list = [];

        if ($product->related_products) {
            foreach (explode(',', $product->related_products) as $related_product) {
                $related_products[] = Product::where('number', $related_product)->first();
            }
        } else {
            $related_products = false;
        }

        if ($pack = PackProduct::select(['pack_id'])->where('product', $product_Id)->get()->toArray()) {
            $pack_list = Pack::whereIn('id', $pack)->get();
        }

        if (preg_match('/(search|clearance|specials)/', $request->server('HTTP_REFERER'))) {
            Session::put('continueShopping', $request->server('HTTP_REFERER'));
        }

        return view('webshop.product', [
            'product'           => $product,
            'discount'          => $discount,
            'related_products'  => $related_products,
            'prevPage'          => $prevPage,
            'pack_list'         => $pack_list,
        ]);
    }
}
