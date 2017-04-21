<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestsAdminRoutes;
use App\Http\Controllers\Admin\DashboardController;
use \Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Admin\Controller as AdminController;

/**
 * Class DashboardControllerTest.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DashboardControllerTest extends \Tests\Feature\TestCase
{
    use DatabaseTransactions, TestsAdminRoutes;

    public function setUp()
    {
        parent::setUp();

        $this->setupUsers();

        $this->routes = [
            route('admin.dashboard'),
            route('admin.dashboard::chart', ['type' => 'orders']),
            route('admin.dashboard::stats')
        ];
    }

    /**
     * @test
     */
    public function extends_admin_controller()
    {
        $this->assertInstanceOf(AdminController::class, new DashboardController);
    }

    /**
     * @test
     */
    public function view_method_returns_view()
    {
        $controller = new DashboardController;

        $this->assertInstanceOf(\Illuminate\View\View::class, $controller->view());
    }

    /**
     * @test
     */
    public function sends_valid_order_chart_data()
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard::chart', ['type' => 'orders']))
            ->assertJson([
                'message' => "Chart data for chart 'orders'",
            ]);
    }

    /**
     * @test
     */
    public function sends_400_error_on_invalid_chart_type()
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard::chart', ['type' => 'invalid']))
            ->assertExactJson([
                'message' => 'Unknown chart type',
            ])
            ->assertStatus(400);
    }
}
