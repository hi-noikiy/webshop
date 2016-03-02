<?php namespace App\Http\Controllers;

use App\Product;
use App\Discount;
use App\Content;
use App\Carousel;
use App\User;

use Carbon\Carbon;

use Auth, App, DB, Response, Redirect, Input, Validator, Session, File, Request, Storage, Hash, Helper;

class AdminController extends Controller
{

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
     * The admin overview page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview()
    {
        $product_import = Content::where('name', 'admin.product_import')->first();
        $discount_import = Content::where('name', 'admin.discount_import')->first();

        // SELECT COUNT(id) FROM orders GROUP BY YEAR(created_at), MONTH(created_at);
        $groupedOrders = App\Order::select(DB::raw("YEAR(created_at) as 'year'"))->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'DESC')->get();

        return view('admin.overview', [
            'product_import'    => $product_import,
            'discount_import'   => $discount_import,
            'years'             => $groupedOrders->toArray(),
        ]);
    }

    public function chart($type)
    {
        if (Request::ajax()) {
            if ($type === 'orders')
            {
                // SELECT COUNT(id) FROM orders GROUP BY YEAR(created_at), MONTH(created_at);
                $groupedOrders = App\Order::select(DB::raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'"))
                    ->where(DB::raw('YEAR(created_at)'), request()->input('year'))
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                    ->get();

                return response()->json($groupedOrders);
            } else
                return response()->json(['Unknown chart type'], 400);
        } else
            return response('Only ajax requests are allowed!', 401);
    }

    /**
     * Display a fancy phpinfo page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function phpinfo()
    {
        return view('admin.phpinfo');
    }

    /**
     * Return the CPU load
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function CPULoad()
    {
        if (Request::ajax()) {
            $uptime = exec('uptime');

            $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
            $max = exec('grep "model name" /proc/cpuinfo | wc -l');

            $data = [
                'load' => $load[0],
                'max' => $max,
            ];

            return Response::json($data);
        } else
            return redirect()->back();
    }

    /**
     * Return the RAM usage
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function RAMLoad()
    {
        if (Request::ajax()) {
            $total = preg_replace("/\D/", "", exec("grep 'MemTotal' /proc/meminfo"));
            $free = preg_replace("/\D/", "", exec("grep 'MemFree' /proc/meminfo"));
            //$buffer	= preg_replace("/\D/", "", exec("grep 'Buffers' /proc/meminfo"));
            //$cached	= preg_replace("/\D/", "", exec("grep 'Cached' /proc/meminfo"));

            $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

            $data = [
                'total' => $total,
                'freePercentage' => $freePercentage,
                'free' => $free
            ];

            return Response::json($data);
        } else
            return redirect()->back();
    }

    /**
     * The import page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        return view('admin.import');
    }


    /**
     * The import was successful :D
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function importSuccess()
    {
        // The type must be set
        if (Session::has('type'))
            return view('admin.importsuccess');
        // Or you will be redirected
        else
            return redirect('admin/import');
    }

    /**
     * Content management page
     *
     * @return $this
     */
    public function contentManager()
    {
        $data = Content::where('hidden', '0')->get();

        return view('admin.managecontent')->with(['data' => $data]);
    }

    /**
     * Get the content that belongs to the page/field
     *
     * @return mixed
     */
    public function getContent()
    {
        if (Request::ajax()) {
            if (Input::has('page')) {
                $data = Content::where('name', Input::get('page'))->firstOrFail();

                return $data->content;
            } else
                abort(404, 'Missing page varable');
        } else
            abort(401, 'Not an ajax request!');
    }

    /**
     * Save the content to the database
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function saveContent()
    {
        if (Input::has('field') && Input::has('content')) {
            $content = Input::get('content');
            $field = Input::get('field');

            Content::where('name', $field)->update(['content' => $content]);

            return redirect('admin/managecontent')->with('status', 'De content is aangepast');
        } else
            return redirect()->back()->withErrors('Content of Field veld leeg');
    }

    /**
     * Show the generate page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generate()
    {
        $content = Content::where('name', 'catalog.footer')->first();

        return view('admin.generate', [
            'currentFooter' => $content->content
        ]);
    }

    /**
     * Generate the catalog PDF file
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateCatalog()
    {
        if (Input::get('footer') !== "") {
            $footer = Content::where('name', 'catalog.footer')->first();

            $footer->content = Input::get('footer');

            $footer->save();

            unset($footer);
        }

        ini_set('memory_limit', '1G');

        $footer = Content::where('name', 'catalog.footer')->first();

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function carousel()
    {
        $carouselData = Carousel::orderBy('Order')->get();

        return view('admin.carousel', [
            'carouselData' => $carouselData,
            'status' => 'ok'
        ]);
    }

    /**
     * Add a carousel slide
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function addSlide()
    {
        if (Input::has('title') && Input::has('caption') && Input::hasFile('image')) {

            $image = Input::file('image');
            $title = Input::get('title');
            $caption = Input::get('caption');

            $validator = Validator::make(
                ['image' => $image],
                ['image' => 'required|image']
            );

            if ($validator->fails())
                return redirect()->back()->withErrors($validator->errors());
            else {
                $slide = new Carousel;

                $slide->Image = $image->getClientOriginalName();
                $slide->Title = $title;
                $slide->Caption = $caption;
                $slide->Order = Carousel::count();

                $slide->save();

                Input::file('image')->move(public_path() . "/img/carousel", $image->getClientOriginalName());

                return redirect()->back()->with('status', "De slide is toegevoegd aan de carousel");
            }
        } else
            return redirect()->back()->withErrors("Een of meer velden zijn niet ingevuld");
    }

    /**
     * Remove a slide from the carousel
     *
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function removeSlide($id)
    {
        if (isset($id) && Carousel::where('id', $id)->count() === 1) {
            $slide = Carousel::find($id);

            if (Storage::disk('local')->exists('/public/img/carousel/' . $slide->Image))
                Storage::disk('local')->delete('/public/img/carousel/' . $slide->Image);

            Carousel::destroy($id);

            return redirect()->back()->with('status', "De slide is verwijderd uit de carousel");
        } else
            return redirect()->back()->withErrors("De slide met id $id bestaat niet");
    }

    /**
     * Edit the slide order number
     *
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editSlide($id)
    {
        if (isset($id) && Carousel::where('id', $id)->count() === 1) {
            if (Input::has('order') && is_numeric(Input::get('order'))) {
                $slide = Carousel::find($id);

                $slide->Order = Input::get('order');

                $slide->save();

                return redirect()->back()->with('status', "Het slide nummer is aangepast");
            } else
                return redirect()->back()->withErrors('Er is een ongeldig slide nummer opgegeven');
        } else
            return redirect()->back()->withErrors("De slide met id $id bestaat niet");
    }

    /**
     * Show the user manager
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userManager()
    {
        return view('admin.usermanager');
    }

    /**
     * Get some user details
     *
     * @return mixed
     */
    public function getUserData()
    {
        if (Request::ajax()) {
            if (Input::has('id')) {
                $userdata = User::where('login', Input::get('id'))->firstOrFail();

                return $userdata;
            } else
                abort(400);
        } else
            abort(405);
    }

    /**
     * Add/update a user
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateUser()
    {
        $validator = Validator::make(Input::all(), [
            'login' => 'required|integer|between:10000,99999',
            'name' => 'required|string',
            'email' => 'required|email',
            'street' => 'required',
            'postcode' => 'required',
            'city' => 'required',
            'active' => 'required',
        ]);


        if (!$validator->fails()) {
            if (Input::get('delete') === '') {
                $user = User::where('login', Input::get('login'));

                $user->delete();

                return redirect()->back()->with(['status' => 'De gebruiker is succesvol verwijderd']);
            } elseif (Input::get('update') === '') {
                if (User::where('login', Input::get('login'))->count() === 1) { // The user exists...
                    $user = User::where('login', Input::get('login'))->first();

                    $user->company = Input::get('name');
                    $user->email = Input::get('email');
                    $user->street = Input::get('street');
                    $user->postcode = Input::get('postcode');
                    $user->city = Input::get('city');
                    $user->active = Input::get('active');

                    $user->save();

                    return redirect()->back()->with(['status' => 'Gebruiker ' . Input::get('login') . ' is aangepast']);
                } else { // The user does not exist...
                    $pass = mt_rand(100000, 999999);
                    $user = new User;

                    $user->login = Input::get('login');
                    $user->company = Input::get('name');
                    $user->email = Input::get('email');
                    $user->street = Input::get('street');
                    $user->postcode = Input::get('postcode');
                    $user->city = Input::get('city');
                    $user->active = Input::get('active');
                    $user->password = Hash::make($pass);

                    $user->save();

                    Session::flash('password', $pass);
                    Session::flash('input', Input::all());

                    return redirect('admin/userAdded');
                }
            } else
                return redirect()->back()->withErrors('Geen actie opgegeven (toevoegen of verwijderen)');
        } else
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput(Input::all());
    }

    /**
     * Show the user added page
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function userAdded()
    {
        if (Session::has('password') && Session::has('input'))
            return view('admin.userAdded')->with(['password' => Session::pull('password'), 'input' => Session::get('input')]);
        else
            return redirect('admin/usermanager');
    }

    /**
     * Generate a pricelist for a specific user
     *
     * @return $this|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generate_pricelist()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required',
            'separator' => 'required',
            'position' => 'required'
        ]);

        if (!$validator->fails() && Input::hasFile('file')) {
            $user_id = Input::get('user_id');
            $file = Input::file('file');
            $separator = Input::get('separator');
            $position = Input::get('position');
            $skip = (int)Input::get('skip');
            $count = 0;

            // Create a filesystem link to the temp file
            $filename = storage_path() . '/prijslijst_' . $user_id . '.txt';

            // Store the path in flash data so the middleware can delete the file afterwards
            Session::flash('file.download', $filename);

            $string = "product;netto prijs;prijs per;registratie eenheid\r\n";

            foreach (file($file->getRealPath()) as $input) {
                if ($count >= $skip) {
                    $linedata = str_getcsv($input, $separator);

                    if (isset($linedata[$position - 1])) {
                        $product = Product::select(['number', 'group', 'price', 'refactor', 'price_per', 'special_price', 'registered_per'])
                            ->where('number', $linedata[$position - 1])
                            ->first();

                        if ($product !== null) {
                            if ($product->special_price === '0.00') {
                                $discount = 1 - (Helper::getProductDiscount($user_id, $product->group, $product->number) / 100);
                                $price = number_format(preg_replace("/\,/", ".", $product->price) * $discount, 2, ",", "");
                            } else {
                                $price = number_format(preg_replace("/\,/", ".", $product->special_price), 2, ",", "");
                            }

                            $string .= $product->number . ";" . $price . ";" . $product->price_per . ";" . $product->registered_per . "\r\n";
                        }
                    }
                }

                $count++;
            }

            // File the file with discount data
            File::put($filename, $string);

            // Return the data as a downloadable file: 'icc_data.txt'
            return Response::download($filename, 'prijslijst_' . $user_id . '.txt');
        } else {
            return redirect('admin/generate')
                ->withErrors((Input::hasFile('file') === false ? "Geen bestand geuploaded" : $validator->errors()))
                ->withInput(Input::all());
        }
    }
}
