<?php namespace App\Http\Controllers;

use App\Content;
use App\Carousel;

class HomeController extends Controller {

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
         * Homepage
         *
         * @return mixed
         */
        public function home()
        {
                $news           = Content::where('name', 'home.news')->first();
                $carouselSlides = Carousel::orderBy('Order')->get();

                return view('home.index')->with(['news' => $news, 'carouselSlides' => $carouselSlides]);
        }

        /**
         * About us
         *
         * @return mixed
         */
        public function about()
        {
                return view('home.about');
        }

        /**
         * About us
         *
         * @return mixed
         */
        public function assortment()
        {
                $manufacturers = json_decode(file_get_contents(public_path() . "/json/manufacturers.json"));

                return view('home.assortment', ['manufacturers' => $manufacturers]);
        }

        /**
         * Contact info
         *
         * @return mixed
         */
        public function contact()
        {
                return view('home.contact');
        }

        /**
         * Downloads
         *
         * @return mixed
         */
        public function downloads()
        {
                $catalogus       = Content::where('name', 'downloads.catalogus')->first();
                $flyers          = Content::where('name', 'downloads.flyers')->first();
                $artikel         = Content::where('name', 'downloads.artikel')->first();

                return view('home.downloads', [
                    'catalogus'         => $catalogus,
                    'flyers'            => $flyers,
                    'artikelbestand'    => $artikel
                ]);
        }

        /**
         * Licenses
         *
         * @return mixed
         */
        public function licenses()
        {
                return view('home.licenses');
        }

        /**
         * Show a pdf inside a view
         *
         * @param $filename
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function download($filename)
        {
                $path = public_path() . "/dl/" . $filename;

                if (!\File::exists($path))
                        abort(404);
                else {
                        if (\File::mimeType($path) === 'application/pdf')
                                return view('home.showfile', ['file' => $filename]);
                        else
                                return response()->download($path, $filename);
                }
        }
}
