<?php

namespace WTG\Services;

use Illuminate\Http\Request;
use WTG\Contracts\Services\RegistrationServiceContract;

/**
 * Registration service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationService implements RegistrationServiceContract
{
    /**
     * Create a registration from request.
     *
     * @param  Request  $request
     * @return bool
     */
    public function createFromRequest(Request $request): bool
    {
        //
    }
}