<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Check the default user login
     *
     * @return void
     */
    public function testSuccessfulUserLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('user', 'username')
            ->type('test', 'password')
            ->press('Login')
            ->see('U bent nu ingelogd')
            ->dontSee('Admin');
    }

    /**
     * Check inactive user login
     *
     * @return void
     */
    public function testInactiveUserLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '0',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('user', 'username')
            ->type('test', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist')
            ->dontSee('Admin');
    }

    /**
     * Check admin user login
     *
     * @return void
     */
    public function testAdminUserLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '1',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('user', 'username')
            ->type('test', 'password')
            ->press('Login')
            ->see('U bent nu ingelogd')
            ->see('Admin');
    }

    /**
     * Check login with wrong username
     *
     * @return void
     */
    public function testIncorrectUsernameLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('baduser', 'username')
            ->type('test', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with wrong password
     *
     * @return void
     */
    public function testIncorrectPasswordLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('user', 'username')
            ->type('wrongpassword', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with empty password
     *
     * @return void
     */
    public function testEmptyFormLogin()
    {
        DB::table('users')->insert([
            'login'     => 'user',
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        $this->visit('/')
            ->type('user', 'username')
            ->type('', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

}
