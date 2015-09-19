<?php namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Product;
use App\User;
use App\Address;
use App\Order;

use DB, Cart, Auth, Input, Session, Request, Redirect, Validator, App;

class WebshopController extends Controller {

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
                $brands         = DB::table('products')->select('brand')->distinct()->get();
                $series         = DB::table('products')->select('series')->distinct()->get();
                $types          = DB::table('products')->select('type')->distinct()->get();

                $data           = array(
                        'brands'        => $brands,
                        'series'        => $series,
                        'types'         => $types
                );

                return view('webshop.main', $data);
        }

        public function register()
        {
                if (Auth::check())
                        return Redirect::to('/account');

                $data = array();

                if (Session::has('registrationData'))
                        $data = Session::get('registrationData');

                return view('webshop.register', $data);
        }

        public function register_check()
        {
                $validator = Validator::make(Input::all(), [
                                'corContactName'        => 'required',
                                'corName'               => 'required',
                                'corAddress'            => 'required',
                                'corPostcode'           => 'required',
                                'corCity'               => 'required',
                                'corPhone'              => 'required',
                                'corEmail'              => 'required|email',
                                'corSite'               => 'url',

                                'delAddress'            => 'required',
                                'delPostcode'           => 'required',
                                'delCity'               => 'required',
                                'delPhone'              => 'required',

                                'betIBAN'               => 'required',
                                'betKvK'                => 'required',
                                'betBTW'                => 'required',

                                'digFactuur'            => 'required|email',
                                'digOrder'              => 'required',
                                'digArtikel'            => 'required',
                        ]);

                if (!$validator->fails())
                        {
                        $data['corContactName'] = Input::get('corContactName');
                        $data['corName']        = Input::get('corName');
                        $data['corAddress']     = Input::get('corAddress');
                        $data['corPostcode']    = Input::get('corPostcode');
                        $data['corCity']        = Input::get('corCity');
                        $data['corContactPhone']= Input::get('corContactPhone');
                        $data['corPhone']       = Input::get('corPhone');
                        $data['corFax']         = (Input::get('corFax') !== false ? Input::get('corFax') : "");
                        $data['corEmail']       = Input::get('corEmail');
                        $data['corSite']        = (Input::get('corSite') !== false ? Input::get('corSite') : "");

                        $data['corIsDel']       = Input::get('corIsDel');

                        $data['delAddress']     = ($data['corIsDel'] ? $data['corAddress'] : Input::get('delAddress'));
                        $data['delPostcode']    = ($data['corIsDel'] ? $data['corPostcode'] : Input::get('delPostcode'));
                        $data['delCity']        = ($data['corIsDel'] ? $data['corCity'] : Input::get('delCity'));
                        $data['delPhone']       = ($data['corIsDel'] ? $data['corPhone'] : Input::get('delPhone'));
                        $data['delFax']         = ($data['corIsDel'] ? $data['corFax'] : (Input::get('delFax') !== false ? Input::get('delFax') : ""));

                        $data['betIBAN']        = Input::get('betIBAN');
                        $data['betKvK']         = Input::get('betKvK');
                        $data['betBTW']         = Input::get('betBTW');

                        $data['digFactuur']     = Input::get('digFactuur');
                        $data['digOrder']       = Input::get('digOrder');
                        $data['digArtikel']     = Input::get('digArtikel');

                        Session::flash('registrationData', $data);

                        if (!$data['corContactName'] || !$data['corName'] || !$data['corAddress'] || !$data['corPostcode'] || !$data['corCity'] || !$data['corPhone'] || !$data['corEmail'] || !$data['delAddress'] || !$data['delPostcode'] || !$data['delCity'] || !$data['delPhone'] || !$data['betIBAN'] /*|| !$data['betBIC']*/ || !$data['betKvK'] || !$data['betBTW']) {
                                return Redirect::back()->withErrors( 'Niet alle vereiste velden zijn ingevuld');
                        } else {
                                \Mail::send('email.registration', $data, function($message)
                                        {
                                                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                                                $message->to('thomas.wiringa@gmail.com'/*Auth::user()->email*/);

                                                $message->subject('Webshop registratie');
                                        });

                                return Redirect::to('/registrationSent');
                        }
                } else
                        return Redirect::back()
                                ->withErrors($validator->errors())
                                ->withInput(Input::all());
        }

        public function registerSent()
        {
                if (Auth::check());
                        return Redirect::to('/account');

                return view('webshop.registerSent');
        }

        /**
         * The search page
         *
         * @return mixed
         */
        public function search()
        {
                $startTime      = microtime(true);
                $str            = Input::get('q');
                $inputBrand     = Input::get('brand');
                $inputSerie     = Input::get('serie');
                $inputType      = Input::get('type');

                $query = DB::table('products')
                        ->orWhere('name', 'LIKE', $str)
                        ->orWhere('number', 'LIKE', $str)
                        ->orWhere('group', 'LIKE', $str)
                        ->orWhere('altNumber', 'LIKE', $str);

                $query->orWhere(function($subQuery)
                {
                        foreach (explode(' ', Input::get('q')) as $word) {
                                // Split the input so the order of the search query doesn't matter
                                $subQuery->where(DB::raw('CONCAT(name, " ", keywords)'), "LIKE", "%{$word}%");
                        }
                });

                if (Input::has('brand')) $query = $query->where('brand', $inputBrand);
                if (Input::has('serie')) $query = $query->where('series', $inputSerie);
                if (Input::has('type'))  $query = $query->where('type', $inputType);

                // Get all the results to filter the brands, series and types from it
                $allResults = $query->orderBy('number', 'asc')->get();

                // Get the paginated results
                $results = $query->paginate(25);

                // Initialize $brands, $series, $types as array
                $brands =
                $series =
                $types = array();

                // Get the brands, series and types from the search results
                foreach ($allResults as $product) {
                        $brands[] = $product->brand;
                        $series[] = $product->series;
                        $types[] = $product->type;
                }

                // Return the search view with the fetched data
                return view('webshop.search', array(
                                'results'       => $results,
                                'brands'        => array_unique($brands),
                                'series'        => array_unique($series),
                                'types'         => array_unique($types),
                                'scriptTime'    => round(microtime(true) - $startTime, 4)
                        )
                );
        }

        /**
         * The product page
         * Will throw 404 error when no product matches the product id
         *
         * @param $product_Id
         * @return mixed
         */
        public function showProduct($product_Id = false)
        {
                if ($product_Id === false)
                    return App::abort(400, 'Missing product number');

                Session::flash('product_id', $product_Id);

                $product                = Product::where('number', $product_Id)->firstOrFail();
                $discount               = (Auth::check() ? getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);
                $prevPage               = Input::get('ref');

                if ($product->related_products)
                        foreach(explode(',', $product->related_products) as $related_product)
                                $related_products[] = Product::where('number', $related_product)->first();
                else
                        $related_products = NULL;


                if (preg_match("/search/", Request::server('HTTP_REFERER')))
                        Session::put('continueShopping', Request::server('HTTP_REFERER'));

                return view('webshop.product', array(
                                'productData'           => $product,
                                'discount'              => $discount,
                                'related_products'      => $related_products,
                                'prevPage'              => $prevPage
                        )
                );
        }

        /**
         * The user will be redirected to the previous page with
         * a message indicating whether the login was successful or not
         *
         * @return mixed
         */
        public function login()
        {
                // Check if the user is already logged in
                if (Auth::check())
                        return Redirect::to('account');

                // Is all the data entered
                if (Input::has('username') && Input::has('password'))
                {
                        // Try to log the user in
                        if (Auth::attempt(array('login' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1), (Input::get('remember_me') === "on" ? true : false)))
                        {
                                if (Auth::user()->cart) {
                                        foreach (unserialize(Auth::user()->cart) as $item) {
                                                // Restore the user's cart
                                                Cart::add($item);
                                        }
                                }

                                return Redirect::back()->with('status', 'U bent nu ingelogd');
                        }
                }

                // The input field(s) is/are empty, go back to the previous page with an error message
                //return Redirect::back()->with(array('error' => , 'username' => Input::get('username')));
                return Redirect::back()
                        ->withErrors('Gebruikersnaam en/of wachtwoord onjuist')
                        ->withInput(Input::except('password'));
        }

        /**
         * The user will be redirected to the main page if the logout was successful
         *
         * @return mixed
         */
        public function logout()
        {
                if (Auth::check())
                {
                        Cart::destroy();
                        Auth::logout();

                        return Redirect::to('/')->with('status', 'U bent nu uitgelogd');
                } else
                        return Redirect::to('/')->withErrors( 'Geen gebruiker ingelogd');
        }

        /**
         * A search page only searching for the specials
         *
         * @return mixed
         */
        public function specials()
        {
                $startTime = microtime(true);
                $inputBrand = Input::get('brand');
                $inputSerie = Input::get('serie');
                $inputType = Input::get('type');

                $query = DB::table('products')->Where('action_type', 'Actie');

                if (Input::has('brand')) $query = $query->where('brand', $inputBrand);
                if (Input::has('serie')) $query = $query->where('series', $inputSerie);
                if (Input::has('type'))  $query = $query->where('type', $inputType);

                // Get all the results to filter the brands, series and types from it
                $allResults = $query->orderBy('number', 'asc')->get();

                // Get the paginated results
                $results = $query->paginate(25);

                // Initialize $brands, $series, $types as array
                $brands =
                $series =
                $types = array();

                // Get the brands, series and types from the search results
                foreach ($allResults as $product) {
                        $brands[] = $product->brand;
                        $series[] = $product->series;
                        $types[] = $product->type;
                }

                // Return the search view with the fetched data
                return view('webshop.altSearch', array(
                                'results'       => $results,
                                'title'         => 'Acties',
                                'brands'        => array_unique($brands),
                                'series'        => array_unique($series),
                                'types'         => array_unique($types),
                                'scriptTime'    => round(microtime(true) - $startTime, 4)
                        )
                );
        }

        /**
         * A search page only searching for the clearance products
         *
         * @return mixed
         */
        public function clearance()
        {
                $startTime = microtime(true);
                $inputBrand = Input::get('brand');
                $inputSerie = Input::get('serie');
                $inputType = Input::get('type');

                $query = DB::table('products')->Where('action_type', 'Opruiming');

                if (Input::has('brand')) $query = $query->where('brand', $inputBrand);
                if (Input::has('serie')) $query = $query->where('series', $inputSerie);
                if (Input::has('type'))  $query = $query->where('type', $inputType);

                // Get all the results to filter the brands, series and types from it
                $allResults = $query->orderBy('number', 'asc')->get();

                // Get the paginated results
                $results = $query->paginate(25);

                // Initialize $brands, $series, $types as array
                $brands =
                $series =
                $types = array();

                // Get the brands, series and types from the search results
                foreach ($allResults as $product) {
                        $brands[] = $product->brand;
                        $series[] = $product->series;
                        $types[] = $product->type;
                }

                // Return the search view with the fetched data
                return view('webshop.altSearch', array(
                                'results'       => $results,
                                'title'         => 'Opruiming',
                                'brands'        => array_unique($brands),
                                'series'        => array_unique($series),
                                'types'         => array_unique($types),
                                'scriptTime'    => round(microtime(true) - $startTime, 4)
                        )
                );
        }

        /**
         * Show the cart
         *
         * @return mixed
         */
        public function viewCart()
        {
                if (Auth::check())
                        $addresses = DB::table('addresses')->where('User_id', Auth::user()->id)->get();
                else
                        return Redirect::to('/#loginModal');

                return view('webshop.cart', array('cart' => Cart::content(), 'addresses' => $addresses));
        }

        /**
         * Add a product to the cart
         *
         * @return mixed
         */
        public function addToCart()
        {
                $number  = Input::get('product');
                $qty     = Input::get('qty');
                $ref     = Input::get('ref');

                $validator = Validator::make(
                        array(
                                'product'       => $number,
                                'qty'           => $qty
                        ),
                        array(
                                'product'       => array('required', 'digits:7'),
                                'qty'           => array('required', 'numeric')
                        )
                );

                if (!$validator->fails())
                {
                        // Load the product data
                        $product     = Product::where('number', $number)->firstOrFail();
                        // Load the user cart data
                        $cartArray   = unserialize(Auth::user()->cart);

                        // Add the product data to the cart data
                        $cartArray[$number] =
                        $productData = array(
                                                'id'      => $product->number,
                                                'name'    => $product->name,
                                                'qty'     => $qty,
                                                'price'   => number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", ""),
                                                'options' => array(
                                                        'korting' => getProductDiscount(Auth::user()->login, $product->group, $product->number)
                                                )
                                        );

                        // Add the product to the cart
                        Cart::add($productData);

                        // Save the updated array to the database
                        $user = User::find(Auth::user()->id);
                        $user->cart = serialize($cartArray);
                        $user->save();

                        if ($ref)
                                return Redirect::to($ref)->with('status', 'Het product ' . $number . ' is toegevoegd aan uw winkelwagen');
                        else
                                return Redirect::to('cart');
                } else {
                        return Redirect::back()
                                ->withErrors($validator->errors());
                }
        }

        /**
         * Modify or remove a product from the cart
         *
         * @return mixed
         */
        public function updateCart()
        {
                $rowId   = Input::get('rowId');
                $qty     = Input::get('qty');
                $artNr   = Input::get('productId');

                $validator = Validator::make(
                        array(
                                'rowId'         => $rowId,
                                'qty'           => $qty
                        ),
                        array(
                                'rowId'         => array('required'),
                                'qty'           => array('required', 'numeric')
                        )
                );

                if (!$validator->fails())
                {
                        if (Input::get('edit') === "")
                        {
                                // Load the user cart data
                                $cartArray   = unserialize(Auth::user()->cart);

                                $cartArray[$artNr]['qty'] = $qty;

                                // Save the updated array to the database
                                $user = User::find(Auth::user()->id);
                                $user->cart = serialize($cartArray);
                                $user->save();

                                Cart::update($rowId, array('qty' => $qty));

                                return Redirect::to('cart')->with('status', 'Uw winkelwagen is geupdatet');
                        } elseif (Input::get('remove') === "")
                        {
                                // Load the user cart data
                                $cartArray   = unserialize(Auth::user()->cart);

                                unset($cartArray[$artNr]);

                                // Save the updated array to the database
                                $user = User::find(Auth::user()->id);
                                $user->cart = serialize($cartArray);
                                $user->save();

                                Cart::remove($rowId);

                                return Redirect::to('cart')->with('status', 'Het product is verwijderd');
                        } else
                                return Redirect::to('cart')->withErrors( 'Er is een fout opgetreden');
                } else
                {
                        $messages = $validator->errors();
                        $msg = '';

                        // Put all the messages in one variable
                        foreach($messages->all() as $key => $message)
                                $msg .= ucfirst($message) . "<br />";

                        return Redirect::to('cart')->withErrors( $msg);
                }
        }

        /**
         * To destroy or not to destroy
         *
         * @return mixed
         */
        public function cartDestroy()
        {
                // Cart::destroy() returns NULL, issue:
                // https://github.com/Crinsane/LaravelShoppingcart/issues/56
                if (!Cart::destroy())
                {
                        $user = User::find(Auth::user()->id);
                        $user->cart = NULL;
                        $user->save();

                        return Redirect::to('/')->with('status', 'Uw winkelwagen is geleegd');
                } else
                {
                        return Redirect::to('cart')->withErrors( 'Er is een fout opgetreden tijden het legen van de winkelwagen');
                }
        }

        /**
         * Mail the order to the company
         *
         * @return mixed
         */
        public function order()
        {
                if (Cart::count(false) !== 0)
                {
                        if (Input::has('addressId'))
                        {
                                if (Input::get('addressId') === '-2')
                                {
                                        $address = new \stdClass();

                                        $address->name       = '';
                                    	$address->street     = 'Wordt gehaald';
                                    	$address->postcode   = '';
                                        $address->city       = '';
                                    	$address->telephone  = '';
                                    	$address->mobile     = '';

                                } else if (Address::where(['id' => Input::get('addressId'), 'User_id' => Auth::user()->id])->first())
                                        $address = Address::where(['id' => Input::get('addressId'), 'User_id' => Auth::user()->id])->first();
                                else
                                        return Redirect::to('/cart')->withErrors( 'Het opgegeven adres hoort niet bij uw account');

                                $data['address'] = $address;
                                $data['cart']    = Cart::content();
                                $data['comment'] = (Input::has('comment') ? Input::get('comment') : false);

                                \Mail::send('email.order', $data, function($message)
                                {
                                        $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                                        $message->to('thomas.wiringa@gmail.com'/*verkoop@wiringa.nl*/);

                                        $message->subject('Webshop order');
                                });

                                $order = new Order();

                                $order->products = serialize(Cart::content());
                                $order->User_id  = Auth::user()->id;

                                $order->save();

                                Session::flash('order', true);

                                Cart::destroy();

                                return Redirect::to('/cart/order/finished');
                        } else
                                return Redirect::to('/cart')->withErrors( 'Geen adres opgegeven');
                } else
                        return Redirect::to('/')->withErrors( 'Er zitten geen producten in uw winkelwagen!');
        }

        /**
         * Show the order finished screen
         *
         * @return mixed
         */
        public function orderFinished()
        {
                if (Session::pull('order'))
                        return view('webshop.finishedOrder');
                else
                        return Redirect::to('/');
        }
}
