<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class WebshopController extends BaseController {

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

                return View::make('webshop.main', $data);
        }

        /**
         * The login page for mobile users
         *
         * @return mixed
         */
        public function loginPage()
        {
                return View::make('webshop.login');
        }

        /**
         * The search page
         *
         * @return mixed
         */
        public function search()
        {
                $startTime = microtime(true);
                $str = Input::get('q');
                $inputBrand = Input::get('brand');
                $inputSerie = Input::get('serie');
                $inputType = Input::get('type');

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
                return View::make('webshop.search', array(
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
        public function showProduct($product_Id)
        {
                $product                = Product::where('number', $product_Id)->firstOrFail();
                $discount               = (Auth::check() ? getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);
                $prevPage               = Input::get('ref');

                if ($product->related_products)
                {
                        foreach(explode(',', $product->related_products) as $related_product)
                                $related_products[] = Product::where('number', $related_product)->first();
                } else
                        $related_products = NULL;


                if (preg_match("/search/", Request::server('HTTP_REFERER')))
                        Session::put('continueShopping', Request::server('HTTP_REFERER'));

                return View::make('webshop.product', array(
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
                if (Auth::check())
                        return Redirect::to('account');

                if (Input::has('username') && Input::has('password'))
                {
                        if (Auth::attempt(array('login' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1), (Input::get('remember_me') === "on" ? true : false)))
                                return Redirect::back()->with('success', 'U bent nu ingelogd');
                }

                // The input field(s) is/are empty, go back to the previous page with an error message
                return Redirect::back()->with(array('error' => 'Gebruikersnaam en/of wachtwoord onjuist', 'username' => Input::get('username')));
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
                        Auth::logout();

                        return Redirect::to('/')->with('success', 'U bent nu uitgelogd');
                } else
                {
                        return Redirect::to('/')->with('error', 'Geen gebruiker ingelogd');
                }
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
                return View::make('webshop.altSearch', array(
                                'results'       => $results,
                                'formAction'    => 'specials',
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
                return View::make('webshop.altSearch', array(
                                'results'       => $results,
                                'formAction'    => 'clearance',
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
                $addresses = DB::table('addresses')->where('User_id', Auth::user()->login)->get();

                return View::make('webshop.cart', array('cart' => Cart::content(), 'addresses' => $addresses));
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
                        App::error(function(ModelNotFoundException $e) use ($number)
                        {
                                return Redirect::back()->with('error', 'Geen product gevonden met nummer: ' . $number);
                        });

                        $product = Product::where('number', $number)->firstOrFail();

                        Cart::add(
                                array(
                                        'id' => $product->number,
                                        'name' => $product->name,
                                        'qty' => $qty,
                                        'price' => number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", ""),
                                        'options' => array(
                                                'korting' => getProductDiscount(Auth::user()->login, $product->group, $product->number)
                                        )
                                )
                        );

                        if ($ref)
                                return Redirect::to($ref)->with('success', 'Het product ' . $number . ' is toegevoegd aan uw winkelwagen');
                        else
                                return Redirect::to('cart/view');
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
                                Cart::update($rowId, array('qty' => $qty));

                                return Redirect::to('cart/view')->with('success', 'Uw winkelwagen is geupdatet');
                        } elseif (Input::get('remove') === "")
                        {
                                Cart::remove($rowId);

                                return Redirect::to('cart/view')->with('success', 'Het product is verwijderd');
                        } else
                        {
                                return Redirect::to('cart/view')->with('error', 'Er is een fout opgetreden');
                        }
                } else
                {
                        $messages = $validator->messages();
                        $msg = '';

                        // Put all the messages in one variable
                        foreach($messages->all() as $key => $message)
                                $msg .= ucfirst($message) . "<br />";

                        return Redirect::to('cart/view')->with('error', $msg);
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
                        $user->cart = '';
                        $user->save();

                        return Redirect::to('/')->with('success', 'Uw winkelwagen is geleegd');
                } else
                {
                        return Redirect::to('cart/view')->with('error', 'Er is een fout opgetreden tijden het legen van de winkelwagen');
                }
        }

        /**
         * The admin login page
         *
         * @return mixed
         */
        public function adminLoginGET()
        {
                if (Auth::check())
                {
                        return Redirect::to('admin');
                } else
                {
                        return View::make('admin.login');
                }
        }

        /**
         * The admin login handler
         *
         * @return mixed
         */
        public function adminLoginPOST()
        {
                if (Input::has('username') && Input::has('password'))
                {
                        if (Auth::attempt(array('login' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1, 'isAdmin' => 1)))
                                return Redirect::back()->with('success', 'U bent nu ingelogd');

                } elseif (Auth::check())
                        return Redirect::to('account');

                // The input field(s) is/are empty, go back to the previous page with an error message
                return Redirect::back()->with('error', 'Gebruikersnaam en/of wachtwoord onjuist')->with('username', Input::get('username'));
        }
}
