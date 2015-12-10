<?php namespace App\Http\Controllers;

use App\Product;
use App\Discount;
use App\Content;
use App\Carousel;
use App\User;

use Auth, App, DB, Response, Redirect, Input, Validator, Session, File, Request, Storage, Hash;

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
                $this->middleware('auth.admin');
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

                        $data   = [
                                'load'  => $load[0],
                                'max'   => $max,
                        ];

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
                        //$buffer	= preg_replace("/\D/", "", exec("grep 'Buffers' /proc/meminfo"));
                        //$cached	= preg_replace("/\D/", "", exec("grep 'Cached' /proc/meminfo"));

                        $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

                        $data 	= [
                                'total'          => $total,
                                'freePercentage' => $freePercentage,
                                'free' 	         => $free
                        ];

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
                        ini_set('memory_limit', '1G');

                        $file = Input::file('productFile');

                        $validator = Validator::make(
                                ['fileType' => $file->getMimeType()],
                                ['fileType' => 'required|string:text/plain|string:text/csv']
                        );

                        if ($validator->fails())
                                return Redirect::back()->withErrors( $validator->errors());
                        else
                        {
                                \Debugbar::disable();
                                header('X-Accel-Buffering: no');
                                // This loop is used to send the first 4096 bytes for the output buffering to work
                                echo "<!--";
                                for ($i=0; $i < 4089; $i++) {
                                        echo "X";
                                }
                                echo "-->";

                                echo "<h1>Product import</h1>";
                                echo "Preparing database transaction.... <br />";
                                echo "[" . "<div style=' position: absolute; top: 84px; left:  821px;'>]</div>";

                                $startTime = microtime(true);

                                $csv       = file($file->getRealPath());
                                $lineCount = count($csv);

                                DB::beginTransaction();

                                try {
                                        // Truncate the products table
                                        (new Product)->newQuery()->delete();

                                        DB::connection()->disableQueryLog();

                                        $line = $lastPercent = 0;

                                        foreach ($csv as $row) {
                                                $row  = preg_replace("/\r\n/", "", $row);
                                                $data = explode(';', $row);

                                                DB::table('products')->insert([
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
                                                        'brand'            => $data[21],
                                                        'series'           => $data[22],
                                                        'type'             => $data[23],
                                                        'special_price'    => ($data[24] === "" ? "0.00" : preg_replace("/\,/", ".", $data[24])),
                                                        'action_type'      => $data[25],
                                                        'keywords'         => $data[26],
                                                        'related_products' => $data[27],
                                                        'catalog_group'    => $data[28],
                                                        'catalog_index'    => $data[29],
                                                ]);

                                                $line++;
                                                $percentage  = round(($line / $lineCount) * 100);

                                                if ($percentage !== $lastPercent)
                                                {
                                                        echo "#";
                                                        echo "<div style='position: absolute; top: 105px; left: 424px;width: 30px;background:white;'>$percentage%</div>";
                                                        ob_flush();
                                                        flush();
                                                }

                                                $lastPercent = $percentage;
                                        }

                                        echo "<br /><br />";

                                        ob_flush();
                                        flush();

                                        sleep(1);

                                        echo "Committing data...<br />";

                                        ob_flush();
                                        flush();

                                        DB::commit();
                                } catch (\Exception $e) {
                                        echo "<br /><br />";
                                        echo "An error has occurred, the database will be rolled back to it's previous state...<br />";

                                        ob_flush();
                                        flush();

                                        DB::rollback();

                                        return Redirect::back()->withErrors("Er is een fout opgetreden, de database is niet aangepast: " . $e->getMessage());
                                }

                                sleep(1);

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::intended('admin/importsuccess')->with(['count' => $lineCount, 'type' => 'product', 'time' => $endTime]);
                        }
                } else
                        return Redirect::back()->withErrors( 'Geen bestand geselecteerd');
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
                        ini_set('memory_limit', '1G');

                        $file = Input::file('discountFile');

                        $validator = Validator::make(
                                ['fileType' => $file->getMimeType()],
                                ['fileType' => 'required|string:text/plain|string:text/csv']
                        );

                        if ($validator->fails())
                                return Redirect::back()->withErrors( $validator->errors());
                        else
                        {
                                header('X-Accel-Buffering: no');
                                \Debugbar::disable();
                                // This loop is used to send the first 4096 bytes for the output buffering to work
                                echo "<!--";
                                for ($i=0; $i < 4089; $i++) {
                                        echo "X";
                                }
                                echo "-->";

                                echo "<h1>Korting import</h1>";
                                echo "Preparing database transaction.... <br />";
                                echo "[" . "<div style=' position: absolute; top: 84px; left:  821px;'>]</div>";

                                $startTime = microtime(true);

                                $csv       = file($file->getRealPath());
                                $lineCount = count($csv);

                                DB::beginTransaction();

                                try {
                                        // Truncate the discount table
                                        (new Discount)->newQuery()->delete();

                                        DB::connection()->disableQueryLog();

                                        $line = $lastPercent = 0;

                                        foreach ($csv as $row) {
                                                $data = explode(';', $row);

                                                DB::table('discounts')->insert([
                                                        'table'         => $data[0],
                                                        'User_id'       => ($data[1] !== "" ? $data[1] : 0),
                                                        'product'       => (is_numeric($data[2]) ? $data[2] : 0),
                                                        'start_date'    => $data[3],
                                                        'end_date'      => $data[4],
                                                        'discount'      => $data[5],
                                                        'group_desc'    => $data[6],
                                                        'product_desc'  => $data[7],
                                                ]);

                                                $line++;

                                                $percentage  = round(($line / $lineCount) * 100);

                                                if ($percentage !== $lastPercent)
                                                {
                                                        echo "#";
                                                        echo "<div style='position: absolute; top: 105px; left: 424px;width: 30px;background:white;'>$percentage%</div>";
                                                        ob_flush();
                                                        flush();
                                                }

                                                $lastPercent = $percentage;
                                        }

                                        echo "<br /><br />";

                                        ob_flush();
                                        flush();

                                        sleep(1);

                                        echo "Committing data...<br />";

                                        ob_flush();
                                        flush();

                                        DB::commit();
                                } catch (\Exception $e) {
                                        echo "<br /><br />";
                                        echo "An error has occurred, the database will be rolled back to it's previous state...<br />";

                                        ob_flush();
                                        flush();

                                        DB::rollback();

                                        return Redirect::back()->withErrors("Er is een fout opgetreden, de database is niet aangepast: " . $e->getMessage());
                                }

                                sleep(1);

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::to('admin/importsuccess')->with(['count' => count($csv), 'time' => $endTime, 'type' => 'korting']);
                        }
                } else
                        return Redirect::back()->withErrors( 'Geen bestand geselecteerd');
        }

        /**
        * This function will handle the image import
        *
        * @return mixed
        */
        public function imageImport()
        {
                if (Input::hasFile('imageFile') && Input::file('imageFile')->isValid())
                {
                        $file      = Input::file('imageFile');
                        $fileName  = $file->getClientOriginalName();
                        $fileMime  = $file->getMimeType();
                        $startTime = microtime(true);

                        $validator = Validator::make(
                                ['fileType' => $file],
                                ['fileType' => 'required|mimes:zip,jpg,png,gif,jpeg']
                        );

                        if ($validator->fails())
                                return Redirect::back()->withErrors( 'Geen geldig bestand geuploaded. Het bestand mag een afbeeling of Zip bestand zijn');
                        else
                        {
                                if ($fileMime === "application/zip")
                                {
                                        // Unzip the files to the product image folder
                                        \Zipper::make($file->getRealPath())->extractTo(public_path() . "/img/products");

                                        // This is used to count the number of files in the zip
                                        $zip   = new \ZipArchive;
                                        $zip->open($file->getRealPath());
                                        $count = $zip->numFiles;
                                } else
                                {
                                        // If it's an image file, move it directly to the product image folder
                                        $file->move(public_path() . "/img/products", $fileName);

                                        $count = 1;
                                }

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::to('/admin/importsuccess')->with(['count' => $count, 'time' => $endTime, 'type' => 'afbeelding']);
                        }
                } else
                        return Redirect::back()->withErrors('Geen bestand geselecteerd of de afbeelding is ongeldig');
        }

        /**
        * This function will handle the image import
        *
        * @return mixed
        */
        public function downloadImport()
        {
                if (Input::hasFile('imageFile') && Input::file('imageFile')->isValid())
                {
                        $file      = Input::file('imageFile');
                        $fileName  = $file->getClientOriginalName();
                        $fileMime  = $file->getMimeType();
                        $startTime = microtime(true);

                        $validator = Validator::make(
                                ['fileType' => $file],
                                ['fileType' => 'required|mimes:zip,pdf']
                        );

                        if ($validator->fails())
                                return Redirect::back()->withErrors( 'Geen geldig bestand geuploaded. Het bestand mag een Zip of PDF bestand zijn');
                        else
                        {

                                // If it's an image file, move it directly to the product image folder
                                $file->move(public_path() . "/dl", $fileName);

                                $count = 1;

                                $endTime = round(microtime(true) - $startTime, 4);

                                return Redirect::to('/admin/importsuccess')->with(['count' => $count, 'time' => $endTime, 'type' => 'download']);
                        }
                } else
                        return Redirect::back()->withErrors('Geen bestand geselecteerd of het bestand is ongeldig');
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

                        Content::where('name', $field)->update(['content' => $content]);

                        return Redirect::to('admin/managecontent')->with('status', 'De content is aangepast');
                } else
                        return Redirect::back()->withErrors( 'Content of Field veld leeg');
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

                $footer = DB::table('text')->where('name', 'catalog.footer')->first();

                $productData = DB::table('products')
                        ->orderBy('catalog_group', 'asc')
                        ->orderBy('group', 'asc')
                        ->orderBy('type', 'asc')
                        ->orderBy('number', 'asc')
                        ->whereNotIn('action_type', ['Opruiming', 'Actie'])
                        ->where('catalog_index', '!=', '')
                        ->get();

                File::put(base_path() . "/resources/assets/catalog.html", view('templates.catalogus', ['products' => $productData]));

                exec('wkhtmltopdf --dump-outline "' . base_path() . '/resources/assets/tocStyle.xml" -B 15mm --footer-center "' . $footer->content . '" --footer-right [page] --footer-font-size 7 "' . base_path() . '/resources/assets/catalog.html" toc --xsl-style-sheet "' . base_path() . '/resources/assets/tocStyle.xsl" "' . public_path() . '/dl/Wiringa\ Catalogus.pdf"');

                return Redirect::intended('/dl/Wiringa\ Catalogus.pdf');
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
                                ['image' => $image],
                                ['image' => 'required|image']
                        );

                        if ($validator->fails())
                                return Redirect::back()->withErrors( $validator->errors());
                        else
                        {
                                $slide = new Carousel;

                                $slide->Image   = $image->getClientOriginalName();
                                $slide->Title   = $title;
                                $slide->Caption = $caption;
                                $slide->Order   = Carousel::count();

                                $slide->save();

                                Input::file('image')->move(public_path() . "/img/carousel", $image->getClientOriginalName());

                                return Redirect::back()->with('status', "De slide is toegevoegd aan de carousel");
                        }
                } else
                        return Redirect::back()->withErrors( "Een of meer velden zijn niet ingevuld");
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

                        return Redirect::back()->with('status', "De slide is verwijderd uit de carousel");
                } else
                        return Redirect::back()->withErrors( "De slide met id $id bestaat niet");
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

                                return Redirect::back()->with('status', "Het slide nummer is aangepast");
                        } else
                                return Redirect::back()->withErrors( 'Er is een ongeldig slide nummer opgegeven');
                } else
                        return Redirect::back()->withErrors( "De slide met id $id bestaat niet");
        }

        /**
        * Show the user manager
        *
        * @return mixed
        */
        public function userManager()
        {
                return view('admin.usermanager');
        }

        /**
        * Get some user details
        *
        * @return json
        */
        public function getUserData()
        {
                if (Request::ajax())
                {
                        if (Input::has('id'))
                        {
                                $userdata = User::where('login', Input::get('id'))->firstOrFail();

                                return Response::json($userdata);
                        } else
                                return App::abort(400);
                } else
                        return App::abort(405);
        }

        /**
        * Add/update a user
        *
        * @return mixed
        */
        public function updateUser()
        {
                $validator = Validator::make(Input::all(), [
                                'login'    => 'required|integer|between:10000,99999',
                                'name'     => 'required|string',
                                'email'    => 'required|email',
                                'street'   => 'required',
                                'postcode' => 'required',
                                'city'     => 'required',
                                'active'   => 'required',
                        ]);


                if (!$validator->fails())
                {
                        if (Input::get('delete') === '')
                        {
                                $user = User::where('login', Input::get('login'));

                                $user->delete();

                                return Redirect::back()->with(['status' => 'De gebruiker is succesvol verwijderd']);
                        } elseif (Input::get('update') === '')
                        {
                                if (User::where('login', Input::get('login'))->count() === 1)
                                { // The user exists...
                                        $user = User::where('login', Input::get('login'))->first();

                                        $user->company  = Input::get('name');
                                        $user->email    = Input::get('email');
                                        $user->street   = Input::get('street');
                                        $user->postcode = Input::get('postcode');
                                        $user->city     = Input::get('city');
                                        $user->active   = Input::get('active');

                                        $user->save();

                                        return Redirect::back()->with(['status' => 'Gebruiker ' . Input::get('login') . ' is aangepast']);
                                } else
                                { // The user does not exist...
                                        $pass = mt_rand(100000, 999999);
                                        $user = new User;

                                        $user->login    = Input::get('login');
                                        $user->company  = Input::get('name');
                                        $user->email    = Input::get('email');
                                        $user->street   = Input::get('street');
                                        $user->postcode = Input::get('postcode');
                                        $user->city     = Input::get('city');
                                        $user->active   = Input::get('active');
                                        $user->password = Hash::make($pass);

                                        $user->save();

                                        Session::flash('password', $pass);
                                        Session::flash('input', Input::all());

                                        return Redirect::to('admin/userAdded');
                                }
                        } else
                                return Redirect::back()->withErrors( 'Geen actie opgegeven (toevoegen of verwijderen)');
                } else
                        return Redirect::back()
                                ->withErrors($validator->errors())
                                ->withInput(Input::all());
        }

        /**
        * Show the user added page
        *
        * @return mixed
        */
        public function userAdded()
        {
                if (Session::has('password') && Session::has('input'))
                        return view('admin.userAdded')->with(['password' => Session::pull('password'), 'input' => Session::get('input')]);
                else
                        return Redirect::to('admin/usermanager');
        }
}
