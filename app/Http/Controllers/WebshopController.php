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
     * Show the registration page
     *
     * @return mixed
     */
    public function register()
    {
        if (Auth::check())
            return redirect('/account');

        $data = [];

        if (Session::has('registrationData'))
            $data = Session::get('registrationData');

        return view('webshop.register', $data);
    }

    /**
     * Verify the registration page
     *
     * @return mixed
     */
    public function register_check()
    {
        $validator = Validator::make(Input::all(), [
            'corContactName' => 'required',
            'corName' => 'required',
            'corAddress' => 'required',
            'corPostcode' => 'required',
            'corCity' => 'required',
            'corPhone' => 'required',
            'corEmail' => 'required|email',
            'corSite' => 'url',

            'delAddress' => 'required',
            'delPostcode' => 'required',
            'delCity' => 'required',
            'delPhone' => 'required',

            'betIBAN' => 'required',
            'betKvK' => 'required',
            'betBTW' => 'required',

            'digFactuur' => 'email',
        ]);

        if (!$validator->fails()) {
            $data['corContactName'] = Input::get('corContactName');
            $data['corName'] = Input::get('corName');
            $data['corAddress'] = Input::get('corAddress');
            $data['corPostcode'] = Input::get('corPostcode');
            $data['corCity'] = Input::get('corCity');
            $data['corContactPhone'] = Input::get('corContactPhone');
            $data['corPhone'] = Input::get('corPhone');
            $data['corFax'] = (Input::get('corFax') !== false ? Input::get('corFax') : "");
            $data['corEmail'] = Input::get('corEmail');
            $data['corSite'] = (Input::get('corSite') !== false ? Input::get('corSite') : "");

            $data['corIsDel'] = Input::get('corIsDel');

            $data['delAddress'] = ($data['corIsDel'] ? $data['corAddress'] : Input::get('delAddress'));
            $data['delPostcode'] = ($data['corIsDel'] ? $data['corPostcode'] : Input::get('delPostcode'));
            $data['delCity'] = ($data['corIsDel'] ? $data['corCity'] : Input::get('delCity'));
            $data['delPhone'] = ($data['corIsDel'] ? $data['corPhone'] : Input::get('delPhone'));
            $data['delFax'] = ($data['corIsDel'] ? $data['corFax'] : (Input::get('delFax') !== false ? Input::get('delFax') : ""));

            $data['betIBAN'] = Input::get('betIBAN');
            $data['betKvK'] = Input::get('betKvK');
            $data['betBTW'] = Input::get('betBTW');

            $data['digFactuur'] = Input::get('digFactuur');
            $data['digOrder'] = Input::get('digOrder');
            $data['digArtikel'] = Input::get('digArtikel');

            Session::flash('registrationData', $data);

            DB::table('registrations')->insert([
                'company' => $data['corName'],
                'formdata' => json_encode($data),
                'created_at' => Carbon::now()
            ]);

            \Mail::send('email.registration', $data, function ($message) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                $message->to('verkoop@wiringa.nl');

                $message->subject('Webshop registratie');
            });

            return redirect('/register/sent');
        } else
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput(Input::all());
    }

    /**
     * Show the registration success page
     *
     * @return mixed
     */
    public function registerSent()
    {
        if (!Session::has('registrationData'))
            return redirect('/register');

        return view('webshop.registerSent');
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
     * The user will be redirected to the previous page with
     * a message indicating whether the login was successful or not
     *
     * @return $this|\Illuminate\Http\RedirectResponse|Redirect
     */
    public function login()
    {
        // Check if the user is already logged in
        if (Auth::check())
            return redirect('account');

        // Is all the data entered
        if (Input::has('username') && Input::has('password')) {
            // Try to log the user in
            if (Auth::attempt(['login' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1], (Input::get('remember_me') === "on" ? true : false))) {
                if (Auth::user()->cart) {
                    foreach (unserialize(Auth::user()->cart) as $item) {
                        // Check if the id is set to prevent problems when reloading the cart
                        if (isset($item['id'])) {
                            // Restore the user's cart
                            Cart::add($item);
                        }
                    }

                }

                return redirect()
                    ->back()
                    ->with('status', 'U bent nu ingelogd');
            }
        }

        // The input field(s) is/are empty, go back to the previous page with an error message
        return redirect()->back()
            ->withErrors('Gebruikersnaam en/of wachtwoord onjuist')
            ->withInput(Input::except('password'));
    }

    /**
     * The user will be redirected to the main page if the logout was successful
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Cart::destroy();
            Auth::logout();

            return redirect()->intended('/')->with('status', 'U bent nu uitgelogd');
        } else
            return redirect('/')->withErrors('Geen gebruiker ingelogd');
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
