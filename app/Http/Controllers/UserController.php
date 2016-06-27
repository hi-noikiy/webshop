<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Cart, Log;

class LoginController extends Controller
{

    /**
     * The user will be redirected to the previous page with
     * a message indicating whether the login was successful or not
     *
     * @param  Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|Redirect
     */
    public function login(Request $request)
    {
        // Check if the user is already logged in
        if (Auth::check())
            return redirect('account');

        // Is all the data entered
        if ($request->has('username') && $request->has('password')) {
            // Try to log the user in
            if (Auth::attempt(['login' => $request->input('username'), 'password' => $request->input('password'), 'active' => 1], ($request->input('remember_me') === "on" ? true : false))) {
                Log::info("User [{$request->input('username')}] logged in successfully");

                if (Auth::user()->cart) {
                    Log::info("Loading shopping cart from database for user [{$request->input('username')}]");

                    foreach (unserialize(Auth::user()->cart) as $item) {
                        // Check if the id is set to prevent problems when reloading the cart
                        if (isset($item['id'])) {
                            // Restore the user's cart
                            Cart::add($item);
                        }
                    }
                }

                return redirect()
                    ->back()
                    ->with('status', 'U bent nu ingelogd');
            }
        }

        Log::info("User [{$request->input('username')}] failed to log in");

        // The input field(s) is/are empty, go back to the previous page with an error message
        return redirect()->back()
            ->withErrors('Gebruikersnaam en/of wachtwoord onjuist')
            ->withInput($request->except('password'));
    }

    /**
     * The user will be redirected to the main page if the logout was successful
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Cart::destroy();
            Auth::logout();

            Log::info("User [" . Auth::user()->login . "] logged out successfully");

            return redirect()->intended('/')->with('status', 'U bent nu uitgelogd');
        } else
            return redirect('/')->withErrors('Geen gebruiker ingelogd');
    }

}