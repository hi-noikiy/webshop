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
                        return App::abort(401, 'Niet ingelogd');
                if (!Auth::user()->isAdmin)
                        return App::abort(403, 'Geen admin account');
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

        /**
         * Product import handler
         *
         * @return mixed
         */
        public function productImport()
        {
                if (Input::hasFile('productFile'))
                {
                        ini_set('memory_limit', '1024M');

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
                                $startTime = microtime(true);

                                Product::truncate();

                                $csv = file($file->getRealPath());

                                DB::transaction(function() use ($csv) {
                                        DB::connection()->disableQueryLog();

                                        foreach ($csv as $row) {
                                                $data = explode(';', $row);

                                                DB::table('products')->insert(array(
                                                                'name'             => $data[0],
                                                                'number'           => $data[3],
                                                                'group'            => $data[4],
                                                                'altNumber'        => $data[5],
                                                                'stockCode'        => $data[7],
                                                                'registered_per'   => $data[8],
                                                                'packed_per'       => $data[9],
                                                                'price_per'        => $data[10],
                                                                'refactor'         => preg_replace("/\,/", ".", $data[12]),
                                                                'supplier'         => $data[13],
                                                                'ean'              => $data[14],
                                                                'image'            => $data[15],
                                                                'length'           => $data[17],
                                                                'price'            => $data[18],
                                                                'vat'              => $data[20],
                                                                'brand'            => $data[22],
                                                                'series'           => $data[23],
                                                                'type'             => $data[24],
                                                                'special_price'    => ($data[25] === "" ? "0.00" : preg_replace("/\,/", ".", $data[25])),
                                                                'action_type'      => $data[26],
                                                                'keywords'         => $data[27],
                                                                'related_products' => $data[28],
                                                        ));
                                        }
                                });

                                DB::commit();

                                $endTime = round(microtime(true) - $startTime, 4);
                                return Redirect::to('admin/importsuccess')->with(array('count' => count($csv), 'type' => 'product', 'time' => $endTime));
                        }
                } else
                        return Redirect::back()->with('error', 'Geen bestand geselecteerd');
        }

        /**
         * This function will handle the discount import
         *
         * @return mixed
         */
        public function discountImport()
        {
                if (Input::hasFile('discountFile'))
                {
                        ini_set('memory_limit', '1024M');

                        $file = Input::file('discountFile');

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
                                $startTime = microtime(true);

                                Discount::truncate();

                                $csv = file($file->getRealPath());
                                
                                DB::transaction(function() use ($csv) {
                                        DB::connection()->disableQueryLog();

                                        foreach ($csv as $row) {
                                                $data = explode(';', $row);

                                                DB::table('discounts')->insert(array(
                                                                'table'         => $data[0],
                                                                'User_id'       => ($data[1] !== "" ? $data[1] : 0),
                                                                'product'       => (is_numeric($data[2]) ? $data[2] : 0),
                                                                'start_date'    => $data[3],
                                                                'end_date'      => $data[4],
                                                                'discount'      => $data[5],
                                                                'group_desc'    => $data[6],
                                                                'product_desc'  => $data[7],
                                                        ));
                                        }
                                });

                                DB::commit();

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::to('admin/importsuccess')->with(array('count' => count($csv), 'time' => $endTime, 'type' => 'discount'));
                        }
                } else
                        return Redirect::back()->with('error', 'Geen bestand geselecteerd');
        }

        /**
         * The import was successful :D
         *
         * @return mixed
         */
        public function importSuccess()
        {
                // The type must be set
                if (Session::has('type'))
                        return View::make('admin.importsuccess');
                // Or you will be redirected
                else
                        return Redirect::to('admin/import');
        }

        /**
         * Content management page
         *
         * @return mixed
         */
        public function manageContent()
        {
                $data = Content::all();

                return View::make('admin.managecontent', array('data' => $data));
        }

        /**
         * Get the content that belongs to the page/field
         *
         * @return mixed
         */
        public function getContent()
        {
                if(Request::ajax())
                {
                        if(Input::has('page'))
                        {
                                $data = Content::where('name', Input::get('page'))->firstOrFail();

                                echo $data->content;
                        } else
                                return App::abort(404, 'Missing page varable');
                } else
                        return App::abort(401, 'Not an ajax request!');
        }

        /**
         * Save the content to the database
         *
         * @return mixed
         */
        public function saveContent()
        {
                if (Input::has('field') && Input::has('content'))
                {
                        $content = Input::get('content');
                        $field = Input::get('field');

                        Content::where('name', $field)->update(array('content' => $content));

                        return Redirect::to('admin/managecontent')->with('success', 'De content is aangepast');
                } else
                        return Redirect::back()->with('error', 'Content of Field veld leeg');
        }

        /**
         * Show the generate page
         *
         * @return mixed
         */
        public function generate()
        {
                return View::make('admin.generate');
        }

        /**
         * Generate the catalog PDF file
         *
         * @return string
         */
        public function generateCatalog()
        { 
                ini_set('memory_limit', '1G');
                $productData = DB::table('products')->get();

                File::put(public_path() . "/assets/catalog.html", View::make('templates.catalogus', array('products' => $productData)));

                exec('wkhtmltopdf toc --xsl-style-sheet "' . public_path() . '/assets/tocStyle.xsl" --footer-right [page] "' . public_path() . '/assets/catalog.html" "' . public_path() . '/assets/catalog.pdf" &');
                
                return "file made";
        }
}
