<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @package WTG
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class Controller extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    abstract public function view();
}