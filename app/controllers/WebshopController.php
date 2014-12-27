<?php

class WebshopController extends BaseController {

        /*
        |--------------------------------------------------------------------------
        | Webshop Controller
        |--------------------------------------------------------------------------
        |
        | This controller will process the requests for the pages:
        |       - Login
        |       - Reset Password
        |       - Main pages
        |       - Product page
        |
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

                // Get the brands from the search results
                foreach ($allResults as $brand) {
                        $brands[] = $brand->brand;
                }

                // Get the series from the search results
                foreach ($allResults as $serie) {
                        $series[] = $serie->series;
                }

                // Get the series from the search results
                foreach ($allResults as $type) {
                        $types[] = $type->type;
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
                $product = Product::where('number', $product_Id)->firstOrFail();

                return View::make('webshop.product', array('productData' => $product));
        }
}
