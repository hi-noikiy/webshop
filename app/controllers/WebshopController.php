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

        // The main webshop page
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

        // Login page
        public function loginPage()
        {
                return View::make('webshop.login');
        }

}
