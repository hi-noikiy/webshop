<?php

class HomeController extends BaseController {

        /*
        |--------------------------------------------------------------------------
        | Default Home Controller
        |--------------------------------------------------------------------------
        |
        | You may wish to use controllers instead of, or in addition to, Closure
        | based routes. That's great! Here is an example controller method to
        | get you started. To route to this controller, just add the route:
        |
        |	Route::get('/', 'HomeController@showWelcome');
        |
        */

        public function home()
        {
                return View::make('home.index');
        }

        public function about()
        {
                $manufacturers = json_decode(file_get_contents(public_path() . "/json/manufacturers.json"));

                return View::make('home.about', array('manufacturers' => $manufacturers));
        }

        public function contact()
        {
                return View::make('home.contact');
        }

        public function downloads()
        {
                return View::make('home.downloads');
        }

}
