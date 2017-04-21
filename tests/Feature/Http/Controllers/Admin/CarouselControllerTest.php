<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestsAdminRoutes;
use \App\Http\Controllers\Admin\CarouselController;
use \Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Http\Controllers\Admin\Controller as AdminController;

/**
 * Class CarouselControllerTest.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CarouselControllerTest extends \Tests\Feature\TestCase
{
    use DatabaseTransactions, TestsAdminRoutes;

    public function setUp()
    {
        parent::setUp();

        $this->setupUsers();

        $this->routes = [
            route('admin.carousel')
        ];
    }

    /**
     * @test
     */
    public function extends_admin_controller()
    {
        $this->assertInstanceOf(AdminController::class, new CarouselController);
    }

    /**
     * @test
     */
    public function view_method_returns_view()
    {
        $controller = new CarouselController;

        $this->assertInstanceOf(\Illuminate\View\View::class, $controller->view());
    }

    /**
     * TODO: Disabled for now
     */
    public function can_create_new_slide_with_valid_input()
    {
        $this->withoutMiddleware()
            ->actingAs($this->adminUser)
            ->post(route('admin.carousel'), [
                'title' => 'Test title',
                'caption' => 'Test caption'
            ])
            ->assertStatus(200);
    }

    /**
     * TODO: Disabled for now
     */
    public function cannot_create_new_slide_with_invalid_input()
    {
        $this->withoutMiddleware()
            ->actingAs($this->adminUser)
            ->post(route('admin.carousel'), [
                'title' => 'Test title',
                'caption' => 'Test caption'
            ])
            ->assertStatus(400)
            ->assertRedirect(route('admin.carousel'));
    }
}