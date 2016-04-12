<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Session, Auth, Helper;

class ProductController extends Controller {

    /**
     * The product page
     * Will throw 404 error when no product matches the product id
     *
     * @param Request $request
     * @param bool $product_Id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct(Request $request, $product_Id = false)
    {
        if ($product_Id === false)
            abort(404);

        Session::flash('product_id', $product_Id);

        $product  = Product::where('number', $product_Id)->firstOrFail();
        $discount = (Auth::check() ? Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);
        $prevPage = $request->get('ref');
        $related_products = [];

        if ($product->related_products)
            foreach (explode(',', $product->related_products) as $related_product)
                $related_products[] = Product::where('number', $related_product)->first();
        else
            $related_products = false;


        if (preg_match("/(search|clearance|specials)/", $request->server('HTTP_REFERER')))
            Session::put('continueShopping', $request->server('HTTP_REFERER'));

        return view('webshop.product', [
                'product'           => $product,
                'discount'          => $discount,
                'related_products'  => $related_products,
                'prevPage'          => $prevPage
            ]
        );
    }

}