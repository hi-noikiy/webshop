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
                        return Redirect::to('admin/login');
                if (!Auth::user()->isAdmin)
                        return App::abort(401, 'Geen admin account!');
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
}
