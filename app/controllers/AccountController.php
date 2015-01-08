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
         * This will be accessed by AJAX
         * and will update the favorites
         *
         * @return mixed
         */
        public function modFav()
        {
                if (Request::ajax())
                {
                        $product = Input::get('product');

                        $validator = Validator::make(
                                array('product' => $product),
                                array('product' => array('required', 'digits:7'))
                        );

                        if (!$validator->fails())
                        {
                                $currentFavorites = unserialize(Auth::user()->favorites);

                                // Remove the product from the favorites if it is already in
                                if (in_array($product, $currentFavorites))
                                {
                                        $key = array_search($product, $currentFavorites);

                                        // Remove the product from the favorites array
                                        unset($currentFavorites[$key]);

                                        // Save the new favorites array to the database
                                        $user = User::find(Auth::user()->id);
                                        $user->favorites = serialize($currentFavorites);
                                        $user->save();

                                        echo 'SUCCESS';
                                        exit();
                                } else
                                {
                                        // Add the product to the favorites array
                                        array_push($currentFavorites, $product);

                                        // Save the new favorites array to the database
                                        $user = User::find(Auth::user()->id);
                                        $user->favorites = serialize($currentFavorites);
                                        $user->save();

                                        echo 'SUCCESS';
                                        exit();
                                }
                        } else
                        {
                                echo 'FAILED';
                                exit();
                        }
                } else
                {
                        return Redirect::back()->with('error', 'Geen AJAX verzoek!');
                }
        }

        /**
         * Check if the product is in the favorites array
         *
         * @return mixed
         */
        public function isFav()
        {
                if (Request::ajax())
                {
                        $product = Input::get('product');

                        $validator = Validator::make(
                                array('product' => $product),
                                array('product' => array('required', 'digits:7'))
                        );

                        if (!$validator->fails())
                        {
                                $currentFavorites = unserialize(Auth::user()->favorites);

                                if (in_array($product, $currentFavorites))
                                {
                                        echo 'IN_ARRAY';
                                        exit();
                                } else
                                {
                                        echo 'NOT_IN_ARRAY';
                                        exit();
                                }
                        } else
                        {
                                echo 'FAILED';
                                exit();
                        }
                } else
                {
                        return Redirect::back()->with('error', 'Geen AJAX verzoek!');
                }
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

                        $validator = Validator::make(
                                array(
                                        'name'          => $name,
                                        'street'        => $street,
                                        'postcode'      => $postcode,
                                        'city'          => $city,
                                        'telephone'     => $telephone,
                                        'mobile'        => $mobile
                                ),
                                array(
                                        'name'          => 'required',
                                        'street'        => array('required', 'regex:/^[a-zA-Z0-9\s]+$/'),
                                        'postcode'      => array('required', 'regex:/^[a-zA-Z0-9\s]+$/', 'between:6,8'),
                                        'city'          => array('required', 'regex:/^[a-zA-Z\s]+$/'),
                                        'telephone'     => array('regex:/^[0-9\s\-]+$/'),
                                        'mobile'        => array('regex:/^[0-9\s\-]+$/')
                                )
                        );

                        if (!$validator->fails())
                        {
                                $address = new Address;

                                $address->name          = $name;
                                $address->street        = $street;
                                $address->postcode      = $postcode;
                                $address->city          = $city;
                                $address->telephone     = $telephone;
                                $address->mobile        = $mobile;
                                $address->User_id       = Auth::user()->login;

                                $address->save();

                                return Redirect::back()->with('success', 'Het adres is toegevoegd');
                        } else
                        {
                                $messages = $validator->messages();
                                $msg = '';

                                foreach($messages->all() as $key => $message)
                                        $msg .= ucfirst($message) . "<br />";

                                return Redirect::back()->with('error', $msg);
                        }

                } else
                {
                        return Redirect::back()->with('error', 'Een of meer vereiste velden zijn leeg');
                }
        }

        /**
         * This function handles the removal of an address
         *
         * @return mixed
         */
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

        /**
         * This will handle the requests for the generation of the discounts file
         *
         * @param $type
         * @param $method
         * @return mixed
         */
        public function generateFile($type, $method)
        {
                if ($type === 'icc')
                {
                        if ($method === 'download')
                        {
                                // Create a filesystem link to the temp file
                                $filename       = storage_path() . '/icc_data' . Auth::user()->login . '.txt';

                                // Remove the file when finished
                                App::finish(function($request, $response) use ($filename)
                                {
                                        unlink($filename);
                                });

                                File::put($filename, AccountController::discountICC());

                                return Response::download($filename);

                        } elseif ($method === 'mail')
                        {
                                $filename = storage_path() . '/icc_data' . Auth::user()->login . '.txt';

                                App::finish(function($request, $response) use ($filename)
                                {
                                        unlink($filename);
                                });

                                File::put($filename, AccountController::discountICC());

                                Mail::send('email.discountfile', array(), function($message) use ($filename)
                                {
                                        $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                                        $message->to('thomas.wiringa@gmail.com'/*Auth::user()->email*/);

                                        $message->attach($filename);
                                });

                                return Redirect::to('account/discountfile')->with('success', 'Het kortingsbestand is verzonden naar ' . Auth::user()->email);
                        } else
                        {
                                return Redirect::to('account/discountfile')->with('error', 'Geen verzendmethode opgegeven');
                        }
                } elseif ($type === 'csv')
                {
                        if ($method === 'download')
                        {
                                // Create a filesystem link to the temp file
                                $filename       = storage_path() . '/icc_data' . Auth::user()->login . '.csv';

                                // Remove the file when finished
                                App::finish(function($request, $response) use ($filename)
                                {
                                        unlink($filename);
                                });

                                File::put($filename, AccountController::discountCSV());

                                return Response::download($filename);

                        } elseif ($method === 'mail')
                        {
                                $filename = storage_path() . '/icc_data' . Auth::user()->login . '.csv';

                                App::finish(function($request, $response) use ($filename)
                                {
                                        unlink($filename);
                                });

                                File::put($filename, AccountController::discountCSV());

                                Mail::send('email.discountfile', array(), function($message) use ($filename)
                                {
                                        $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                                        $message->to('thomas.wiringa@gmail.com'/*Auth::user()->email*/);

                                        $message->attach($filename);
                                });

                                return Redirect::to('account/discountfile')->with('success', 'Het kortingsbestand is verzonden naar ' . Auth::user()->email);
                        } else
                        {
                                return Redirect::to('account/discountfile')->with('error', 'Geen verzendmethode opgegeven');
                        }
                } else
                {
                        return Redirect::to('account/discountfile')->with('error', 'Ongeldig bestands type');
                }
        }

        /**
         * This function will generate the data for the ICC file
         *
         * @return string
         */
        private function discountICC()
        {
                /**
                 * These variables are static.
                 * We only need to set them once
                 */
                $GLN = 8714253038995;
                $empty1 = '       ';
                $debiteur = Auth::user()->login;
                $empty2 = '               ';
                $date = date('Ymd');
                $version = '1.1  ';
                $name = Auth::user()->name;

                /*
                 * Used in the rows containing the discounts
                 */
                $korting2 = '00000';
                $korting3 = '00000';
                $nettoprijs = '000000000';
                $startdatum = $date;
                $einddatum = 99991231;

                while (strlen($name) <= 70) {
                        $name .= ' ';
                }

                $text = '';

                /*
                 * Append the "Groepsgebonden" discounts to the ICC file
                 */
                $query = DB::table('discounts')
                        ->where('User_id', $debiteur)
                        ->where('table', 'VA-220')
                        ->where('group_desc', '!=', 'Vervallen');

                $groep_korting = $query->get();
                $count = $query->count();

                foreach ($groep_korting as $korting) {
                        $groepsnummer = $korting->product;
                        while (strlen($groepsnummer) < 20) {
                                $groepsnummer .= ' ';
                        }
                        $artikelnummer = '                    '; //20 empty positions
                        $omschrijving = preg_replace("/(\r)|(\n)/", "", $korting->group_desc);
                        while (strlen($omschrijving) < 50) {
                                $omschrijving .= ' ';
                        }
                        $korting1 = '0' . preg_replace("/\,/", "", $korting->discount);
                        while (strlen($korting1) < 5) {
                                $korting1 .= '0';
                        }

                        $text .= $groepsnummer . $artikelnummer . $omschrijving . $korting1 . $korting2 . $korting3 . $nettoprijs . $startdatum . $einddatum . "\r\n";
                }

                /*
                 * Append the "Standaard" discounts to the ICC file
                 */
                $query = DB::table('discounts')
                        ->where('table', 'VA-221')
                        ->where('group_desc', '!=', 'Vervallen')
                        ->whereNotIn('product', function($query) use ($debiteur) {
                                $query->select('product')
                                        ->from('discounts')
                                        ->where('table', 'VA-220')
                                        ->where('User_Id', $debiteur);
                        });

                $default_korting = $query->get();
                $count = $query->count() + $count;

                foreach ($default_korting as $korting) {
                        $groepsnummer = $korting->product;
                        while (strlen($groepsnummer) < 20) {
                                $groepsnummer .= ' ';
                        }
                        $artikelnummer = '                    '; //20 empty positions
                        $omschrijving = preg_replace("/(\r)|(\n)/", "", $korting->group_desc);
                        while (strlen($omschrijving) < 50) {
                                $omschrijving .= ' ';
                        }
                        $korting1 = '0' . preg_replace("/\,/", "", $korting->discount);
                        while (strlen($korting1) < 5) {
                                $korting1 .= '0';
                        }

                        $text .= $groepsnummer . $artikelnummer . $omschrijving . $korting1 . $korting2 . $korting3 . $nettoprijs . $startdatum . $einddatum . "\r\n";
                }

                /*
                 * Append the "Productgebonden" discounts to the ICC file
                 */
                $query = DB::table('discounts')
                        ->where('User_id', $debiteur)
                        ->where('table', 'VA-260');

                $product_korting = $query->get();
                $count = $query->count() + $count;

                foreach ($product_korting as $korting) {
                        $groepsnummer = '                    '; //20 empty positions
                        $artikelnummer = $korting->product;
                        while (strlen($artikelnummer) < 20) {
                                $artikelnummer .= ' ';
                        }
                        $omschrijving = preg_replace("/(\r)|(\n)/", "", $korting->product_desc);
                        while (strlen($omschrijving) < 50) {
                                $omschrijving .= ' ';
                        }
                        $korting1 = '0' . preg_replace("/\,/", "", $korting->discount);
                        while (strlen($korting1) < 5) {
                                $korting1 .= '0';
                        }

                        $text .= $groepsnummer . $artikelnummer . $omschrijving . $korting1 . $korting2 . $korting3 . $nettoprijs . $startdatum . $einddatum . "\r\n";
                }

                /*
                 * Prepend the first row last so the count doesnt mess up.
                 */
                $count = sprintf("%'06d", $count);
                $text = $GLN . $empty1 . $debiteur . $empty2 . $date . $count . $version . $name . "\r\n" . $text;

                return $text;
        }

        /**
         * This function will generate the data for the CSV file
         *
         * @return string
         */
        private function discountCSV()
        {
                // Static variables
                $firstRow 	= 'Artikelnr;Omschrijving;Kortingspercentage;ingangsdatum' . "\r\n";
                $debiteur 	= Auth::user()->login;
                $date		= date('Y-m-d');
                $delimiter	= ';';

                $text 		= $firstRow;

                /*
                 * Append the "Groepsgebonden" discounts to the CSV file
                 */
                $query = DB::table('discounts')
                        ->where('User_id', $debiteur)
                        ->where('table', 'VA-220')
                        ->where('group_desc', '!=', 'Vervallen');

                $groep_korting = $query->get();

                foreach ($groep_korting as $korting) {
                        $groepsnummer 	= $korting->product;
                        $omschrijving 	= preg_replace("/(\r)|(\n)/", "", $korting->group_desc);
                        $korting1 	= $korting->discount . "%";

                        $text 	       .= $groepsnummer . $delimiter . $omschrijving . $delimiter . $korting1 . $delimiter . $date . "\r\n";
                }

                /*
                 * Append the "Standaard" discounts to the CSV file
                 */
                $query = DB::table('discounts')
                        ->where('table', 'VA-221')
                        ->where('group_desc', '!=', 'Vervallen')
                        ->whereNotIn('product', function($query) use ($debiteur) {
                                $query->select('product')
                                        ->from('discounts')
                                        ->where('table', 'VA-220')
                                        ->where('User_Id', $debiteur);
                        });

                $default_korting = $query->get();

                foreach ($default_korting as $korting) {
                        $groepsnummer 	= $korting->product;
                        $omschrijving 	= preg_replace("/(\r)|(\n)/", "", $korting->group_desc);
                        $korting1 	= $korting->discount . "%";

                        $text 	       .= $groepsnummer . $delimiter . $omschrijving . $delimiter . $korting1 . $delimiter . $date . "\r\n";
                }

                /*
                 * Append the "Productgebonden" discounts to the CSV file
                 */
                $query = DB::table('discounts')
                        ->where('User_id', $debiteur)
                        ->where('table', 'VA-260');

                $product_korting = $query->get();

                foreach ($product_korting as $korting) {
                        $artikelnummer 	= $korting->product;
                        $omschrijving 	= preg_replace("/(\r)|(\n)/", "", $korting->product_desc);
                        $korting1 	= $korting->discount . "%";

                        $text 	       .= $artikelnummer . $delimiter . $omschrijving . $delimiter . $korting1 . $delimiter . $date . "\r\n";
                }

                return $text;
        }
}
