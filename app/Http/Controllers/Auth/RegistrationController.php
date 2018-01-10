<?php

namespace WTG\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use WTG\Contracts\Services\RegistrationServiceContract;

/**
 * Registration controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationController extends Controller
{
    /**
     * @var RegistrationServiceContract
     */
    protected $registrationService;

    /**
     * RegistrationController constructor.
     *
     * @param  RegistrationServiceContract  $registrationService
     */
    public function __construct(RegistrationServiceContract $registrationService)
    {
        $this->registrationService = $registrationService;
    }


    /**
     * Registration page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return view('pages.auth.registration');
    }

    /**
     * Save the registration form.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function putAction(Request $request): RedirectResponse
    {
        try {
            $this->registrationService->createFromRequest($request);
        } catch (\Exception $e) {
            return back()->withInput($request->input());
        }

        return redirect(route('home'))
            ->with('status', __('Uw registratie aanvraag is in goede orde ontvangen.'));
    }
}