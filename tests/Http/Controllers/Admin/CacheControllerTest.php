<?php

use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\Controller as AdminController;

class CacheControllerTest extends TestCase
{
    /**
     * @var App\User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->createUser(true, true, '11111', '11111');
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

    /**
     * @test
     */
    public function shows_message_if_opcache_is_disabled()
    {
        $this->actingAs($this->user)
            ->get(route('admin.cache'))
            ->see("De PHP module 'opcache' staat niet aan");
    }
}
