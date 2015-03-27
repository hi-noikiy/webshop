<?php

class AdminController extends BaseController {

        /*
        |--------------------------------------------------------------------------
        | Home Controller
        |--------------------------------------------------------------------------
        |
        | This controller will process the requests for the pages:
        |       - Home
        |       - About us
        |       - Contact
        |       - Downloads
        |       - Licenses
        */

        /**
         * This will check if the user is logged in.
         * If the user is not logged in then they will be redirected to the login page
         * as they are not allowed to access this Controller without admin authentication.
         */
        public function __construct()
        {
                $this->beforeFilter('auth');

                if (!Auth::check())
                        return Redirect::to('admin/login');
                if (!Auth::user()->isAdmin)
                        return App::abort(401, 'Geen admin account!');
        }

        /**
         * The admin overview page
         *
         * @return mixed
         */
        public function overview()
        {
                return View::make('admin.overview');
        }

        /**
         * Return the CPU load
         *
         * @return string
         */
        public function CPULoad()
        {
                $uptime = exec('uptime');

                $load 	= array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
                $max 	= exec('grep "model name" /proc/cpuinfo | wc -l');

                $data 	= array(
                        'load' 	=> $load[0],
                        'max' 	=> $max,
                );

                return Response::json($data);
        }

        /**
         * Return the RAM usage
         *
         * @return string
         */
        public function RAMLoad()
        {
                $total 	= preg_replace("/\D/", "", exec("grep 'MemTotal' /proc/meminfo"));
                $free	= preg_replace("/\D/", "", exec("grep 'MemFree' /proc/meminfo"));

                $data 	= array(
                        'total' => $total,
                        'free' 	=> $free
                );

                return Response::json($data);
        }

        /**
         * The import page
         *
         * @return mixed
         */
        public function import()
        {
                return View::make('admin.import');
        }

        public function productImport()
        {
                if (Input::hasFile('productFile'))
                {
                        $file = Input::file('productFile');

                        $validator = Validator::make(
                                array(
                                        'fileType' => $file->getMimeType(),
                                ),
                                array(
                                        'fileType' => 'required|string:text/plain|string:text/csv'
                                )
                        );

                        if ($validator->fails())
                        {
                                return Redirect::back()->with('error', $validator->messages());
                        } else {
                                Product::truncate();
                                ini_set('memory_limit', '512M');

                                $csv = file($file->getRealPath());
                                $count = 0;

                                foreach ($csv as $row) {
                                        $data = explode(';', $row);

                                        $product = new Product;

                                        $product->name = $data[0];
                                        $product->number = $data[3];
                                        $product->group = $data[4];
                                        $product->altNumber = $data[5];
                                        $product->stockCode = $data[7];
                                        $product->registered_per = $data[8];
                                        $product->packed_per = $data[9];
                                        $product->price_per = $data[10];
                                        $product->refactor = preg_replace("/\,/", ".", $data[12]);
                                        $product->supplier = $data[13];
                                        $product->ean = $data[14];
                                        $product->image = $data[15];
                                        $product->length = $data[17];
                                        $product->price = $data[18];
                                        $product->vat = $data[20];
                                        $product->brand = $data[22];
                                        $product->series = $data[23];
                                        $product->type = $data[24];
                                        $product->special_price = ($data[25] === "" ? "0.00" : preg_replace("/\,/", ".", $data[25]));
                                        $product->action_type = $data[26];
                                        $product->keywords = $data[27];
                                        $product->related_products = $data[28];

                                        $product->save();

                                        unset($product);
                                        $count++;
                                }

                                return Redirect::back()->with('success', 'Het artikelbestand is geupload. ' . $count .' producten geimporteerd');
                        }
                } else
                        return Redirect::back()->with('error', 'Geen bestand geselecteerd');
        }
}
