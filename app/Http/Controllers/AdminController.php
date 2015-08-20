<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Discount;
use App\Content;
use App\Carousel;

use Auth, App, DB, Response, Redirect, Input, Validator, Session, File, Request, Storage;

class AdminController extends Controller {

        /*
        |--------------------------------------------------------------------------
        | Admin Controller
        |--------------------------------------------------------------------------
        |
        | This controller will process the requests for the pages:
        |       - Admin
        |       - Import
        |       - Edit content
        |       - Generate
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
                return view('admin.overview');
        }

        /**
         * Return the CPU load
         *
         * @return string
         */
        public function CPULoad()
        {
                if (Request::ajax())
                {
                        $uptime = exec('uptime');

                        $load   = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
                        $max    = exec('grep "model name" /proc/cpuinfo | wc -l');

                        $data   = array(
                                'load'  => $load[0],
                                'max'   => $max,
                        );

                        return Response::json($data);
                } else
                        return Redirect::back();
        }

        /**
         * Return the RAM usage
         *
         * @return string
         */
        public function RAMLoad()
        {
                if (Request::ajax())
                {
                        $total 	= preg_replace("/\D/", "", exec("grep 'MemTotal' /proc/meminfo"));
                        $free	= preg_replace("/\D/", "", exec("grep 'MemFree' /proc/meminfo"));

                        $data 	= array(
                                'total' => $total,
                                'free' 	=> $free
                        );

                        return Response::json($data);
                } else
                        return Redirect::back();
        }

        /**
         * The import page
         *
         * @return mixed
         */
        public function import()
        {
                return view('admin.import');
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
                        \Debugbar::disable();
                        ini_set('memory_limit', '1G');

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
                                // This loop is used to send the first 4096 bytes for the output buffering to work
                                echo "<!--";
                                for ($i=0; $i < 4089; $i++) { 
                                        echo "X";
                                }
                                echo "-->";

                                echo "Preparing database transaction.... <br />";
                                echo "[";

                                $startTime = microtime(true);

                                Product::truncate();

                                $csv       = file($file->getRealPath());
                                $lineCount = count($csv);

                                DB::beginTransaction();

                                //DB::transaction(function() use ($csv) {
                                        DB::connection()->disableQueryLog();

                                        $line = $lastPercent = 0;

                                        foreach ($csv as $row) {
                                                $row  = preg_replace("/\r\n/", "", $row);
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
                                                                'catalog_group'    => $data[29],
                                                                'catalog_index'    => $data[30],
                                                        ));

                                                $line++;
                                                $percentage  = round(($line / $lineCount) * 100);
                                                
                                                if ($percentage !== $lastPercent)
                                                {
                                                        echo "#";
                                                        ob_flush();
                                                        flush();
                                                }

                                                $lastPercent = $percentage;
                                        }
                                //});
                                echo "] <br />";

                                ob_flush();
                                flush();

                                sleep(1);

                                DB::commit();

                                sleep(1);

                                echo "Committing data...<br />";

                                ob_flush();
                                flush();

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::intended('admin/importsuccess')->with(['count' => $lineCount, 'type' => 'product', 'time' => $endTime]);
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
                                // This loop is used to send the first 4096 bytes for the output buffering to work
                                echo "<!--";
                                for ($i=0; $i < 4089; $i++) { 
                                        echo "X";
                                }
                                echo "-->";

                                echo "Preparing database transaction.... <br />";
                                echo "[";

                                $startTime = microtime(true);

                                Discount::truncate();

                                $csv       = file($file->getRealPath());
                                $lineCount = count($csv);

                                DB::beginTransaction();

                                //DB::transaction(function() use ($csv) {
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
                                                
                                                $line++;
                                                
                                                $percentage  = round(($line / $lineCount) * 100);
                                                
                                                if ($percentage !== $lastPercent)
                                                {
                                                        echo "#";
                                                        ob_flush();
                                                        flush();
                                                }

                                                $lastPercent = $percentage;
                                        }
                                //});
                                echo "] <br />";

                                ob_flush();
                                flush();

                                sleep(1);

                                DB::commit();

                                echo "Committing data...<br />";

                                ob_flush();
                                flush();

                                sleep(1);

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
                // Disable the debugbar or it will overload the memory
                \Debugbar::disable();
                // The type must be set
                if (Session::has('type'))
                        return view('admin.importsuccess');
                // Or you will be redirected
                else
                        return Redirect::to('admin/import');
        }

        /**
         * Content management page
         *
         * @return mixed
         */
        public function contentManager()
        {
                $data = Content::where('visible', '1')->get();

                return view('admin.managecontent')->with(['data' => $data]);
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
                $content = DB::table('text')->where('name', 'catalog.footer')->first();

                return view('admin.generate')->with(['currentFooter' => $content->content]);
        }

        /**
         * Generate the catalog PDF file
         *
         * @return string
         */
        public function generateCatalog()
        {
                if (Input::get('footer') !== "")
                {
                        $footer = Content::where('name', 'catalog.footer');

                        $footer->content = Input::get('footer');

                        $footer->save();

                        unset($footer);
                }

                ini_set('memory_limit', '1G');

                $footer = Content::where('name', 'catalog.footer')->get();

                $productData = DB::table('products')
                                    ->orderBy('catalog_group', 'asc')
                                    ->orderBy('group', 'asc')
                                    ->orderBy('type', 'asc')
                                    ->orderBy('number', 'asc')
                                    ->whereNotIn('action_type', ['Opruiming', 'Actie'])
                                    ->where('catalog_index', '!=', '')
                                    ->get();

                File::put(base_path() . "/resources/assets/catalog.html", view('templates.catalogus', array('products' => $productData)));

                exec('wkhtmltopdf --dump-outline "' . base_path() . '/resources/assets/tocStyle.xml" -B 15mm --footer-center "' . $footer . '" --footer-right [page] --footer-font-size 7 "' . base_path() . '/resources/assets/catalog.html" toc --xsl-style-sheet "' . base_path() . '/resources/assets/tocStyle.xsl" "' . public_path() . '/dl/catalog.pdf"');
                
                return Redirect::intended('/dl/catalog.pdf');
        }

        /**
         * Carousel manager
         *
         * @return mixed
         */
        public function carousel()
        {
                $carouselData = Carousel::orderBy('Order')->get();

                return view('admin.carousel')->with(['carouselData' => $carouselData, 'status' => 'ok']);
        }

        /**
         * Add a carousel slide
         *
         * @return mixed
         */
        public function addSlide()
        {
                if (Input::has('title') && Input::has('caption') && Input::hasFile('image'))
                {

                        $image   = Input::file('image');
                        $title   = Input::get('title');
                        $caption = Input::get('caption');

                        $validator = Validator::make(
                                array(
                                        'image' => $image,
                                ),
                                array(
                                        'image' => 'required|image'
                                )
                        );

                        if ($validator->fails())
                        {
                                return Redirect::back()->with('error', $validator->messages());
                        } else
                        {
                                $slide = new Carousel;

                                $slide->Image   = $image->getClientOriginalName();
                                $slide->Title   = $title;
                                $slide->Caption = $caption;
                                $slide->Order   = Carousel::count();

                                $slide->save();

                                Input::file('image')->move(public_path() . "/img/carousel", $image->getClientOriginalName());

                                return Redirect::back()->with('success', "De slide is toegevoegd aan de carousel");
                        }
                } else
                        return Redirect::back()->with('error', "Een of meer velden zijn niet ingevuld");
        }

        /**
         * Remove a slide from the carousel
         *
         * @var integer
         * @return mixed
         */
        public function removeSlide($id)
        {
                if (isset($id) && Carousel::where('id', $id)->count() === 1)
                {
                        $slide = Carousel::find($id);

                        if (Storage::disk('local')->exists('/public/img/carousel/' . $slide->Image))
                                Storage::disk('local')->delete('/public/img/carousel/' . $slide->Image);

                        Carousel::destroy($id);

                        return Redirect::back()->with('success', "De slide is verwijderd uit de carousel");
                } else
                        return Redirect::back()->with('error', "De slide met id $id bestaat niet");
        }

        /**
         * Edit the slide order number
         *
         * @var integer
         * @return mixed
         */
        public function editSlide($id)
        {
                if (isset($id) && Carousel::where('id', $id)->count() === 1)
                {
                        if (Input::has('order') && is_numeric(Input::get('order')))
                        {
                                $slide = Carousel::find($id);

                                $slide->Order = Input::get('order');

                                $slide->save();

                                return Redirect::back()->with('success', "Het slide nummer is aangepast");
                        } else
                                return Redirect::back()->with('error', 'Er is een ongeldig slide nummer opgegeven');
                } else
                        return Redirect::back()->with('error', "De slide met id $id bestaat niet");
        }
}
