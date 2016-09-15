<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class AdminTest extends TestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditUserGetUserDetailsWithExistingUser()
    {
        $this->createCompany();
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin/api/company?id=12345')
            ->seeJson([
                'message' => 'User details for user 12345'
            ])
            ->assertResponseStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditUserGetUserDetailsWithMissingInput()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin/api/company')
            ->seeJsonEquals([
                'message' => "Missing request parameter: `id`"
            ])
            ->assertResponseStatus(400);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditUserGetUserDetailsWithNonExistingUser()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin/api/company?id=54321')
            ->seeJsonEquals([
                'message' => 'No user found with login 54321'
            ])
            ->assertResponseStatus(404);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditUserGetUserDetailsWithPartialInput()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin/api/company?id=1234')
            ->seeJsonEquals([
                'message' => 'No user found with login 1234'
            ])
            ->assertResponseStatus(404);
    }
}
