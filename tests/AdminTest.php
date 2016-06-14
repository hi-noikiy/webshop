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
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin/api/user?id=13370')
            ->seeJsonEquals([
                'message' => 'User details for user 13370',
                'payload' => $user->toArray()
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
            ->get('/admin/api/user')
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
            ->get('/admin/api/user?id=07331')
            ->seeJsonEquals([
                'message' => 'No user found with login 07331'
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
            ->get('/admin/api/user?id=1337')
            ->seeJsonEquals([
                'message' => 'No user found with login 1337'
            ])
            ->assertResponseStatus(404);
    }

    /**
     * Create an admin user
     *
     * @return mixed
     */
    private function createUser()
    {
        User::create([
            'login' => '13370',
            'password' => bcrypt('password'),
            'company' => 'company',
            'isAdmin' => 1
        ]);

        return User::whereLogin('13370')->first();
    }
}
