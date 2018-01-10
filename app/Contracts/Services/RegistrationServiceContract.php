<?php

namespace WTG\Contracts\Services;

use Illuminate\Http\Request;

/**
 * Registration service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface RegistrationServiceContract
{
    /**
     * Create a registration from request.
     *
     * @param  Request  $request
     * @return bool
     */
    public function createFromRequest(Request $request): bool;
}