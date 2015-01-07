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
                $orderCount = DB::table('orders')->where('User_id', Auth::user()->login)->count();

                return View::make('account.overview', array('orderCount' => $orderCount));
        }

        /**
         * The change password page (GET Request)
         *
         * @return mixed
         */
        public function changePassGET()
        {
                return View::make('account.changePass');
        }

        public function changePassPOST()
        {
                if (Input::has('oldPass') && Input::has('newPass') && Input::has('newPassVerify'))
                {
                        $oldPass        = Input::get('oldPass');
                        $newPass        = Input::get('newPass');
                        $newPassVerify  = Input::get('newPassVerify');

                        if (Auth::validate(array('login' => Auth::user()->login, 'password' => $oldPass)))
                        {
                                if ($newPass === $newPassVerify)
                                {
                                        $hashedPass     = Hash::make($newPass);
                                        $user           = User::find(Auth::id());

                                        $user->password = $hashedPass;

                                        $user->save();

                                        return Redirect::to('account')->with('success', 'Uw wachtwoord is gewijzigd');
                                } else
                                {
                                        return Redirect::to('account/changepassword')->with('error', 'De nieuwe wachtwoorden komen niet overeen');
                                }
                        } else
                        {
                                Log::warning('User: ' . Auth::user()->login . ' tried to change password but entered the wrong password.');
                                return Redirect::to('account/changepassword')->with('error', 'Het oude wachtwoord is onjuist!');
                        }
                } else
                {
                        return Redirect::to('account/changepassword')->with('error', 'Niet alle velden zijn ingevuld');
                }
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

        /**
         * The address list page
         *
         * @return mixed
         */
        public function addresslist()
        {
                $addressList = DB::table('addresses')->where('User_id', Auth::user()->login)->get();

                return View::make('account.addresslist', array('addresslist' => $addressList));
        }

        /**
         * Handle the add address request
         *
         * @return mixed
         */
        public function addAddress()
        {
                if (Input::has('name') && Input::has('street') && Input::has('postcode') && Input::has('city'))
                {
                        $name           = Input::get('name');
                        $street         = Input::get('street');
                        $postcode       = Input::get('postcode');
                        $city           = Input::get('city');
                        $telephone      = (Input::has('telephone') ? Input::get('telephone') : '');
                        $mobile         = (Input::has('mobile') ? Input::get('mobile') : '');

                        $address = new Address;

                        $address->name          = $name;
                        $address->street        = $street;
                        $address->postcode      = $postcode;
                        $address->city          = $city;
                        $address->telephone     = $telephone;
                        $address->mobile        = $mobile;
                        $address->User_id       = Auth::user()->login;

                        $address->save();

                        return Redirect::to('account/addresslist')->with('success', 'Het adres is toegevoegd');
                } else
                {
                        return Redirect::to('account/addresslist')->with('error', 'Een of meer vereiste velden zijn leeg');
                }
        }

        public function removeAddress()
        {
                if (Input::has('id'))
                {
                        $address = Address::find(Input::get('id'));

                        if (!empty($address) && $address->User_id === Auth::user()->login)
                        {
                                $address->delete();

                                return Redirect::to('account/addresslist')->with('success', 'Het adres is verwijderd');
                        } else
                        {
                                return Redirect::to('account/addresslist')->with('error', 'Het adres bestaat niet of behoort niet bij uw account');
                        }
                } else
                {
                        return Redirect::to('account/addresslist')->with('error', 'Geen adres id aangegeven');
                }
        }

        /**
         * The user is able to download their discounts file from here in ICC and CSV format
         *
         * @return mixed
         */
        public function discountfile()
        {
                return View::make('account.discountfile');
        }
}
