<?php

class AccountController extends BaseController {

        /*
        |--------------------------------------------------------------------------
        | Account Controller
        |--------------------------------------------------------------------------
        |
        | This controller will process the requests for the pages:
        |       - Overview
        |       - Change Password
        |       - Favorites
        |       - Address list
        |       - Order history
        |       - ICC/CSV file generation page
        |
        */

        /**
         * This will check if the user is logged in.
         * If the user is not logged in then they will be redirected to the login page
         * as they are not allowed to access this Controller without authentication.
         */
        public function __construct()
        {
                $this->beforeFilter('auth');

                if (!Auth::check())
                        return Redirect::to('login');
        }

        /**
         * The overview for the account page
         *
         * @return mixed
         */
        public function overview()
        {
                return View::make('account.overview');
        }

        /**
         * The change password page
         *
         * @return mixed
         */
        public function changePass()
        {
                return View::make('account.changePass');
        }

        /**
         * This will fetch the favorites list from the database and
         * transform it into a list categorised by series
         *
         * @return mixed
         */
        public function favorites()
        {
                $favoritesArray = unserialize(Auth::user()->favorites);
                $seriesData     = array();
                $productGroup   = array();

                // Get the product data
                $productData    = DB::table('products')->whereIn('number', $favoritesArray)->get();

                // Store each serie from the products in a seperate array for categorisation
                foreach ($productData as $product)
                        array_push($seriesData, $product->series);

                // Only keep the unique values
                $seriesData = array_unique($seriesData);

                // Put the product and serie data in a new array
                foreach ($seriesData as $key => $serie) {
                        foreach ($productData as $product) {
                                if ($product->series == $serie) {
                                        $productGroup[$serie][] = $product;
                                }
                        }
                }

                return View::make('account.favorites', array(
                                'favorites'     => $productData,
                                'discounts'     => getProductDiscount(Auth::user()->login),
                                'groupData'     => $productGroup
                        )
                );
        }

        /**
         * This page will show the orderhistory
         *
         * @return mixed
         */
        public function orderhistory()
        {
                $orderList = DB::table('orders')->where('User_id', Auth::user()->login)->get();

                return View::make('account.orderhistory', array('orderlist' => $orderList));
        }

        public function addresslist()
        {
                $addressList = DB::table('addresses')->where('User_id', Auth::user()->login)->get();

                return View::make('account.addresslist', array('addresslist' => $addressList));
        }

        public function discountfile()
        {
                return View::make('account.discountfile');
        }
}
