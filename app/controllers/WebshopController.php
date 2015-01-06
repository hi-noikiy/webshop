<?php

class WebshopController extends BaseController {

        /*
        |--------------------------------------------------------------------------
        | Webshop Controller
        |--------------------------------------------------------------------------
        |
        | This controller will process the requests for the pages:
        |       - Login/Logout
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
                $product  = Product::where('number', $product_Id)->firstOrFail();
                $discount = (Auth::check() ? getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);

                return View::make('webshop.product', array(
                                'productData' => $product,
                                'discount' => $discount
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
                if (Input::has('username') && Input::has('password'))
                {
                        if (Auth::attempt(array('login' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1), true))
                        {
                                Session::put('id', Auth::user()->login);
                                return Redirect::back()->with('success', 'U bent nu ingelogd');
                        }
                }

                // The input field(s) is/are empty, go back to the previous page with an error message
                return Redirect::back()->with('error', 'Gebruikersnaam en/of wachtwoord onjuist')->with('username', Input::get('username'));
        }

        /**
         * The user will be redirected to the main page
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
}
