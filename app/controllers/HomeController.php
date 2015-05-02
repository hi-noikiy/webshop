<?php

class HomeController extends BaseController {

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
                $news      = Content::where('name', 'home.news')->first();

                return View::make('home.index', array('news' => $news));
        }

        /**
         * About us
         *
         * @return mixed
         */
        public function about()
        {
                $manufacturers = json_decode(file_get_contents(public_path() . "/json/manufacturers.json"));

                return View::make('home.about', array('manufacturers' => $manufacturers));
        }

        /**
         * Contact info
         *
         * @return mixed
         */
        public function contact()
        {
                return View::make('home.contact');
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

                return View::make('home.downloads', array('catalogus' => $catalogus, 'flyers' => $flyers, 'artikelbestand' => $artikel));
        }

        /**
         * Licenses
         *
         * @return mixed
         */
        public function licenses()
        {
                return View::make('home.licenses');
        }
}
