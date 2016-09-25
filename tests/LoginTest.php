<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Check the default user login.
     *
     * @return void
     */
    public function testSuccessfulUserLogin()
    {
        $this->createCompany();
        $this->createUser();

        $this->visit('/')
            ->type('12345', 'username')
            ->type('12345', 'company')
            ->type('password', 'password')
            ->press('Login')
            ->see('U bent nu ingelogd')
            ->dontSee('Admin');
    }

    /**
     * Check inactive user login.
     *
     * @return void
     */
//    public function testInactiveUserLogin()
//    {
//        $this->createCompany(['active' => false]);
//        $this->createUser(false, true);
//
//        $this->visit('/')
//            ->type('12345', 'username')
//            ->type('12345', 'company')
//            ->type('password', 'password')
//            ->press('Login')
//            ->see('Gebruikersnaam en/of wachtwoord onjuist')
//            ->dontSee('Admin');
//    }

    /**
     * Check admin user login.
     *
     * @return void
     */
    public function testAdminUserLogin()
    {
        $this->createCompany();
        $this->createUser(true);

        $this->visit('/')
            ->type('12345', 'username')
            ->type('12345', 'company')
            ->type('password', 'password')
            ->press('Login')
            ->see('U bent nu ingelogd')
            ->see('Admin');
    }

    /**
     * Check login with wrong username.
     *
     * @return void
     */
    public function testIncorrectUsernameLogin()
    {
        $this->createCompany();
        $this->createUser();

        $this->visit('/')
            ->type('baduser', 'username')
            ->type('12345', 'company')
            ->type('password', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with wrong password.
     *
     * @return void
     */
    public function testIncorrectCompanyLogin()
    {
        $this->createCompany();
        $this->createUser();

        $this->visit('/')
            ->type('12345', 'username')
            ->type('badcompany', 'company')
            ->type('password', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with wrong password.
     *
     * @return void
     */
    public function testIncorrectPasswordLogin()
    {
        $this->createCompany();
        $this->createUser();

        $this->visit('/')
            ->type('12345', 'username')
            ->type('12345', 'company')
            ->type('bad_password', 'password')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }

    /**
     * Check login with empty password.
     *
     * @return void
     */
    public function testEmptyFormLogin()
    {
        $this->createCompany();
        $this->createUser();

        $this->visit('/')
            ->press('Login')
            ->see('Gebruikersnaam en/of wachtwoord onjuist');
    }
}
