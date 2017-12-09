<?php

namespace WTG\Http\Controllers\Auth;

use WTG\Models\Company;
use Illuminate\View\View;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Login controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Request handler.
     *
     * @param  Request  $request
     * @return View
     */
    public function getAction(Request $request)
    {
        return view('pages.auth.login');
    }

    /**
     * Attempt to login.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function postAction(Request $request)
    {
        /** @var Company $company */
        $company = Company::customerNumber($request->input('company'))->first();

        if ($company === null) {
            return $this->failedAuthentication();
        }

        $login_data = [
            'company_id' => $company->getAttribute('id'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'active' => true
        ];

        if (!$this->attemptLogin($login_data, $request->input('remember', false))) {
            return $this->failedAuthentication();
        }

        return $this->successAuthentication($company);
    }

    /**
     * Login failed handler.
     *
     * @return RedirectResponse
     */
    protected function failedAuthentication()
    {
        \Log::info("[Login] Customer '".request('company')."' - '".request('username')."' failed to login");

        return back()
            ->withErrors(trans("auth.login.failed"));
    }

    /**
     * Login success handler.
     *
     * @param  Company  $company
     * @return RedirectResponse
     */
    protected function successAuthentication(Company $company)
    {
        \Log::info("[Login] Customer '".request('company')."' - '".request('username')."' has logged in");

        return redirect(request('toUrl') ?: route('home'))
            ->with('status', trans('auth.login.success', ['name' => $company->getAttribute('name')]));
    }

    /**
     * Attempt the login.
     *
     * @param  array  $data
     * @param  bool  $remember
     * @return bool
     */
    protected function attemptLogin(array $data, bool $remember = false)
    {
        return auth()->attempt($data, $remember);
    }
}
