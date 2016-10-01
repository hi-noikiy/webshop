<?php

namespace App\Http\Controllers\Admin;

use Analytics;
use App;
use App\Carousel;
use App\Company;
use App\Content;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use App\Description;
use DB;
use File;
use Helper;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Response;
use Session;
use Spatie\Analytics\Period;
use Storage;
use Validator;

/**
 * Class AdminController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AdminController extends Controller
{
    /**
     * The admin overview page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        $product_import = Content::where('name', 'admin.product_import')->first();
        $discount_import = Content::where('name', 'admin.discount_import')->first();

        // SELECT COUNT(id) FROM orders GROUP BY YEAR(created_at), MONTH(created_at);
        $groupedOrders = App\Order::select(DB::raw("YEAR(created_at) as 'year'"))->groupBy(DB::raw('YEAR(created_at)'))->orderBy('year', 'DESC')->get();

        try {
            $analytics = Analytics::fetchTopBrowsers(Period::days(365));
        } catch (\Exception $e) {
            $analytics = $e;
        }

        return view('admin.overview', [
            'product_import'    => $product_import,
            'discount_import'   => $discount_import,
            'years'             => $groupedOrders->toArray(),
            'browsers'          => $analytics,
        ]);
    }

    /**
     * Display a fancy phpinfo page.
     *
     * @return \Illuminate\View\View
     */
    public function phpinfo()
    {
        return view('admin.phpinfo');
    }

    /**
     * The import page.
     *
     * @return \Illuminate\View\View
     */
    public function import()
    {
        return view('admin.import');
    }

    /**
     * The import was successful :D.
     *
     * @return \Illuminate\View\View|Redirect
     */
    public function importSuccess()
    {
        // The type must be set
        if (Session::has('type')) {
            return view('admin.importsuccess');
        }
        // Or you will be redirected
        else {
            return redirect('admin/import');
        }
    }

    /**
     * Content management page.
     *
     * @return \Illuminate\View\View
     */
    public function contentManager()
    {
        $data = Content::where('hidden', '0')->get();

        return view('admin.managecontent')->with(['data' => $data]);
    }

    /**
     * Save the content to the database.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function saveContent()
    {
        if (Input::has('field') && Input::has('content')) {
            $content = Input::get('content');
            $field = Input::get('field');

            Content::where('name', $field)->update(['content' => $content]);

            return redirect('admin/managecontent')->with('status', 'De content is aangepast');
        } else {
            return redirect()->back()->withErrors('Content of Field veld leeg');
        }
    }

    /**
     * Save the product description.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDescription(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product' => 'required',
        ]);

        if ($validator->passes()) {
            $content = $request->input('content');
            $product = $request->input('product');

            if (null === Product::findByNumber($product)) {
                return redirect()
                    ->back()
                    ->withErrors('Geen product gevonden met nummer ' . $product);
            }

            $description = Description::firstOrCreate([
                'product_id' => $product,
            ]);

            $description->value = $content;
            $description->save();

            return redirect()
                ->back()
                ->with('status', 'De product omschrijving is aangepast');
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Show the generate page.
     *
     * @return \Illuminate\View\View
     */
    public function generate()
    {
        $content = Content::where('name', 'catalog.footer')->first();

        return view('admin.generate', [
            'currentFooter' => $content->content,
        ]);
    }

    /**
     * Generate the catalog PDF file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateCatalog(Request $request)
    {
        if ($request->input('footer') !== '') {
            $footer = Content::where('name', 'catalog.footer')->first();

            $footer->content = $request->input('footer');

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

        File::put(base_path().'/resources/assets/catalog.html', view('templates.catalogus', ['products' => $productData]));

        exec('wkhtmltopdf --dump-outline "'.base_path().'/resources/assets/tocStyle.xml" -B 15mm --footer-center "'.$footer->content.'" --footer-right [page] --footer-font-size 7 "'.base_path().'/resources/assets/catalog.html" toc --xsl-style-sheet "'.base_path().'/resources/assets/tocStyle.xsl" "'.public_path().'/dl/Wiringa Catalogus.pdf"');

        return Redirect::intended('/dl/Wiringa Catalogus.pdf');
    }

    /**
     * Carousel manager.
     *
     * @return \Illuminate\View\View
     */
    public function carousel()
    {
        return view('admin.carousel', [
            'carouselData' => Carousel::orderBy('Order')->get(),
        ]);
    }

    /**
     * Add a carousel slide.
     *
     * @return \Illuminate\Http\RedirectResponse
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

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator->errors());
            } else {
                $slide = new Carousel();

                $slide->Image = $image->getClientOriginalName();
                $slide->Title = $title;
                $slide->Caption = $caption;
                $slide->Order = Carousel::count();

                $slide->save();

                Input::file('image')->move(public_path('img/carousel'), $image->getClientOriginalName());

                return redirect()
                    ->back()
                    ->with('status', 'De slide is toegevoegd aan de carousel');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors('Een of meer velden zijn niet ingevuld');
        }
    }

    /**
     * Remove a slide from the carousel.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeSlide($id)
    {
        if (isset($id) && Carousel::where('id', $id)->count() === 1) {
            $slide = Carousel::find($id);

            if (Storage::disk('local')->exists('/public/img/carousel/'.$slide->Image)) {
                Storage::disk('local')->delete('/public/img/carousel/'.$slide->Image);
            }

            Carousel::destroy($id);

            return redirect()
                ->back()
                ->with('status', 'De slide is verwijderd uit de carousel');
        } else {
            return redirect()
                ->back()
                ->withErrors("De slide met id $id bestaat niet");
        }
    }

    /**
     * Edit the slide order number.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editSlide($id)
    {
        if (isset($id) && Carousel::where('id', $id)->count() === 1) {
            if (Input::has('order') && is_numeric(Input::get('order'))) {
                $slide = Carousel::find($id);

                $slide->Order = Input::get('order');

                $slide->save();

                return redirect()
                    ->back()
                    ->with('status', 'Het slide nummer is aangepast');
            } else {
                return redirect()
                    ->back()
                    ->withErrors('Er is een ongeldig slide nummer opgegeven');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors("De slide met id $id bestaat niet");
        }
    }

    /**
     * Show the user manager.
     *
     * @return \Illuminate\View\View
     */
    public function userManager()
    {
        return view('admin.usermanager');
    }

    /**
     * Add/update a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'   => 'required|integer|between:10000,99999',
            'company_name' => 'required|string',

            'address'  => 'required',
            'postcode' => 'required',
            'city'     => 'required',

            'email'  => 'required|email',
            'active' => 'required',
        ]);

        if ($validator->passes()) {
            if ($request->get('delete') === '') {
                // Get the company
                $company = Company::whereLogin($request->input('company_id'))->first();

                if ($company) {
                    // Remove associated users
                    $company->users->delete();

                    // Remove the company
                    $company->delete();

                    return redirect()
                        ->back()
                        ->with('status', 'Het bedrijf en bijbehorende gegevens zijn verwijderd');
                } else {
                    return redirect()
                        ->back()
                        ->withInput($request->input())
                        ->withErrors('Geen bedrijf gevonden met login naam '.$request->input('company_id'));
                }
            } elseif ($request->get('update') === '') {
                if ($company = Company::whereLogin($request->input('company_id'))->first()) {
                    $company->login = $request->input('company_id');
                    $company->company = $request->input('company_name');
                    $company->street = $request->input('address');
                    $company->postcode = $request->input('postcode');
                    $company->city = $request->input('city');
                    $company->active = $request->input('active');

                    $company->save();

                    \Log::info('Company '.$company->login.' has been updated by an admin');

                    $user = $company->mainUser;

                    $user->username = $request->input('company_id');
                    $user->company_id = $request->input('company_id');
                    $user->email = $request->input('email');

                    $user->save();

                    \Log::info('User '.$user->username.' has been updated by an admin');

                    return redirect()
                        ->back()
                        ->with('status', 'Bedrijf '.$company->company_id.' is aangepast');
                } else {
                    $pass = mt_rand(100000, 999999);

                    $company = new Company();

                    $company->login = $request->input('company_id');
                    $company->company = $request->input('company_name');
                    $company->street = $request->input('address');
                    $company->postcode = $request->input('postcode');
                    $company->city = $request->input('city');
                    $company->active = $request->input('active');

                    $company->save();

                    $user = new User();

                    $user->username = $request->input('company_id');
                    $user->company_id = $request->input('company_id');
                    $user->email = $request->input('email');
                    $user->manager = true;
                    $user->password = bcrypt($pass);

                    $user->save();

                    Session::flash('password', $pass);
                    Session::flash('input', $request->all());

                    return redirect('admin/userAdded');
                }
            } else {
                return redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors('Geen actie opgegeven (update of verwijderen)');
            }
        } else {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }
    }

    /**
     * Show the user added page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function userAdded()
    {
        if (Session::has('password') && Session::has('input')) {
            return view('admin.userAdded')
                ->with([
                    'password' => Session::pull('password'),
                    'input' => Session::get('input'),
                ]);
        } else {
            return redirect('admin/usermanager');
        }
    }

    /**
     * Generate a pricelist for a specific user.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function generate_pricelist()
    {
        $validator = Validator::make(Input::all(), [
            'user_id'   => 'required',
            'separator' => 'required',
            'position'  => 'required',
        ]);

        if (! $validator->fails() && Input::hasFile('file')) {
            $user_id = Input::get('user_id');
            $file = Input::file('file');
            $separator = Input::get('separator');
            $position = Input::get('position');
            $skip = (int) Input::get('skip');
            $count = 0;

            // Create a filesystem link to the temp file
            $filename = storage_path().'/prijslijst_'.$user_id.'.txt';

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
                                $price = number_format(preg_replace("/\,/", '.', $product->price) * $discount, 2, ',', '');
                            } else {
                                $price = number_format(preg_replace("/\,/", '.', $product->special_price), 2, ',', '');
                            }

                            $string .= $product->number.';'.$price.';'.$product->price_per.';'.$product->registered_per."\r\n";
                        }
                    }
                }

                $count++;
            }

            // File the file with discount data
            File::put($filename, $string);

            // Return the data as a downloadable file: 'icc_data.txt'
            return Response::download($filename, 'prijslijst_'.$user_id.'.txt');
        } else {
            return redirect('admin/generate')
                ->withErrors((Input::hasFile('file') === false ? 'Geen bestand geuploaded' : $validator->errors()))
                ->withInput(Input::all());
        }
    }
}
