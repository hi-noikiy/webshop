<?php

namespace Tests;

/**
 * Class TestsAdminRoutes
 *
 * @package Tests
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait TestsAdminRoutes
{
    /**
     * @var \App\User
     */
    protected $adminUser;

    /**
     * @var \App\User
     */
    protected $normalUser;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * Setup an admin and normal user
     */
    protected function setupUsers()
    {
        $this->adminUser = $this->createUser(true, true, '11111', '11111');
        $this->normalUser = $this->createUser(false, true, '22222', '22222');
    }

    /**
     * @test
     */
    public function test_get_routes()
    {
        foreach ($this->routes as $route) {
            $this->actingAs($this->normalUser)
                ->get($route)
                ->assertStatus(302)
                ->assertRedirect('/account');

            $this->actingAs($this->adminUser)
                ->get($route)
                ->assertStatus(200);
        }
    }
}