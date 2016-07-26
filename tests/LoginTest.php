<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a test user
     *
     * @param  bool $admin
     * @param  bool $manager
     * @param  bool $active
     */
    private function createTestUser($admin = false, $manager = true, $active = true)
    {
        DB::table('users')->insert([
            'username'     => 'user',
            'company_id'   => 10000,
            'email'     => 'test@example.com',
            'active'    => $active,
            'isAdmin'   => $admin,
            'password'  => bcrypt('test'),
            'manager'   => $manager
        ]);
    }

    /**
     * Check the default user login
     *
     * @return void
     */
    public function testSuccessfulUserLogin()
    {
        $this->createTestUser();

        $this->visit('/')
            ->type('user', 'username')
            ->type('10000', 'company')
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
        $this->createTestUser(false, true, false);

        $this->visit('/')
            ->type('user', 'username')
            ->type('10000', 'company')
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
        $this->createTestUser(true);

        $this->visit('/')
            ->type('user', 'username')
            ->type('10000', 'company')
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
        $this->createTestUser();

        $this->visit('/')
            ->type('baduser', 'username')
            ->type('10000', 'company')
            ->type('test', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with wrong password
     *
     * @return void
     */
    public function testIncorrectCompanyLogin()
    {
        $this->createTestUser();

        $this->visit('/')
            ->type('user', 'username')
            ->type('badcompany', 'company')
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
        $this->createTestUser();

        $this->visit('/')
            ->type('user', 'username')
            ->type('10000', 'company')
            ->type('badpassword', 'password')
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
        $this->createTestUser();

        $this->visit('/')
            ->type('user', 'username')
            ->type('', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

}
