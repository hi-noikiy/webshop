<?php namespace App\Http\Controllers;

use App\Product;
use App\Order;
use Carbon\Carbon;

use DB, Cart, Auth, Input, Session, Request, Redirect, Validator, App, Helper;

class WebshopController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Webshop Controller
    |--------------------------------------------------------------------------
    |
    | This controller will process the requests for the pages:
    |       - (admin) Login/Logout
    |       - Reset Password
    |       - Main pages
    |       - Product page
    |       - Search
    */

    /**
     * The main webshop page
     *
     * @return mixed
     */
    public function main()
    {
        $brands = DB::table('products')->select('brand')->distinct()->get();
        $series = DB::table('products')->select('series')->distinct()->get();
        $types = DB::table('products')->select('type')->distinct()->get();

        // Sort the object arrays
        usort($brands, function ($a, $b) {
            return strcmp(strtolower($a->brand), strtolower($b->brand));
        });

        usort($series, function ($a, $b) {
            return strcmp(strtolower($a->series), strtolower($b->series));
        });

        usort($types, function ($a, $b) {
            return strcmp(strtolower($a->type), strtolower($b->type));
        });
        
        return view('webshop.main', [
            'brands' => array_pluck($brands, 'brand'),
            'series' => array_pluck($series, 'series'),
            'types' => array_pluck($types, 'type'),
        ]);
    }

    /**
     * The product page
     * Will throw 404 error when no product matches the product id
     *
     * @param bool $product_Id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct($product_Id = false)
    {
        if ($product_Id === false)
            abort(404);

        Session::flash('product_id', $product_Id);

        $product = Product::where('number', $product_Id)->firstOrFail();
        $discount = (Auth::check() ? Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);
        $prevPage = Input::get('ref');

        if ($product->related_products)
            foreach (explode(',', $product->related_products) as $related_product)
                $related_products[] = Product::where('number', $related_product)->first();
        else
            $related_products = NULL;


        if (preg_match("/(search|clearance|specials)/", Request::server('HTTP_REFERER')))
            Session::put('continueShopping', Request::server('HTTP_REFERER'));

        return view('webshop.product', [
                'productData' => $product,
                'discount' => $discount,
                'related_products' => $related_products,
                'prevPage' => $prevPage
            ]
        );
    }

    /**
     * Add the products from a previous order to the cart
     *
     * @param $orderId
     * @return mixed
     */
    public function reorder($orderId)
    {
        $order = Order::where('User_id', Auth::user()->login)
            ->where('id', $orderId)
            ->firstOrFail();

        $order  = unserialize($order->products);
        $errors = false;

        foreach ($order as $item) {
            if (Product::where('number', $item['id'])->count()) {
                $product = Product::where('number', $item['id'])->firstOrFail();
                $cartData = [
                    'id' => $product->number,
                    'name' => $product->name,
                    'qty' => $item['qty'],
                    'price' => number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", ""),
                    'options' => [
                        'special' => false,
                        'korting' => Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number)
                    ]
                ];

                // Add the product to the cart
                Cart::add($cartData);
            } else {
                $errors = true;
            }
        }

        if ($errors) {
            return redirect('cart')
                ->withErrors('Sommige producten konden niet toegevoegd worden aan uw winkelmandje');
        } else {
            return redirect('cart')
                ->with('status', 'De order producten zijn in uw winkelmandje geplaatst.');
        }
    }

    /**
     * Show the order finished screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Redirect
     */
    public function orderFinished()
    {
        if (Session::pull('order'))
            return view('webshop.finishedOrder');
        else
            return redirect('/');
    }
}
