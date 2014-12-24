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
        |
        */

        // Homepage
        public function home()
        {
                return View::make('home.index');
        }

        // About us
        public function about()
        {
                $manufacturers = json_decode(file_get_contents(public_path() . "/json/manufacturers.json"));

                return View::make('home.about', array('manufacturers' => $manufacturers));
        }

        // Contact
        public function contact()
        {
                return View::make('home.contact');
        }

        // Downloads
        public function downloads()
        {
                return View::make('home.downloads');
        }

}
