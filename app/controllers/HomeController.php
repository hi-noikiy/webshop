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
                return View::make('home.index');
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
                return View::make('home.downloads');
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
