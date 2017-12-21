<?php

namespace WTG\Http\Controllers\Account\Dashboard;

use WTG\Models\Contact;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\CustomerContract;
use WTG\Http\Requests\Account\Dashboard\UpdateContactEmailRequest;

/**
 * Contact email controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account\Dashboard
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ContactEmailController extends Controller
{
    /**
     * Update the contact email address.
     *
     * @param  UpdateContactEmailRequest  $request
     * @return JsonResponse
     */
    public function putAction(UpdateContactEmailRequest $request): JsonResponse
    {
        /** @var CustomerContract $customer */
        $customer = $request->user();
        /** @var Contact $contact */
        $contact = $customer->getContact();

        $contact->contactEmail($request->input('email'));
        $success = $contact->save();
        $code = $success ? 200 : 500;

        return response()->json([
            'success' => $success,
            'message' => $success ? __('Het e-mailadres is opgeslagen.') : __('Er is een fout opgetreden tijdens het opslaan'),
            'code' => $code
        ], $code);
    }
}