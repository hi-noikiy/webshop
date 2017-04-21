<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\Feature\TestCase;
use Tests\TestsAdminRoutes;
use App\Http\Controllers\Admin\CacheController;
use \Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Admin\Controller as AdminController;

/**
 * Class CacheControllerTest.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CacheControllerTest extends TestCase
{
    use DatabaseTransactions, TestsAdminRoutes;

    public function setUp()
    {
        parent::setUp();

        $this->setupUsers();

        $this->routes = [
            route('admin.cache')
        ];
    }

    /**
     * @test
     */
    public function extends_admin_controller()
    {
        $this->assertInstanceOf(AdminController::class, new CacheController);
    }

    /**
     * @test
     */
    public function view_method_returns_view()
    {
        $controller = new CacheController;

        $this->assertInstanceOf(\Illuminate\View\View::class, $controller->view());
    }
}
