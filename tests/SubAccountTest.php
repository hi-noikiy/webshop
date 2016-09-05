<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Company;
use App\User;

class SubAccountTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the product method with an existing product
     *
     * @return void
     */
    public function testIfManagerHasSubAccountsNavigationItem()
    {
        $this->createCompany();
        $user = $this->createUser(false, true);

        $this->actingAs($user)
            ->visit('account')
            ->see('Sub-Accounts');
    }

    /**
     * Test the product method with an existing product
     *
     * @return void
     */
    public function testIfNormalAccountCannotAccessManagerRoute()
    {
        $this->createCompany();
        $user = $this->createUser(false, false);

        $this->actingAs($user)
            ->visit('account')
            ->dontSee('Sub-Accounts');
    }

    public function testSubAccountCreation()
    {
        $this->createCompany();
        $user = $this->createUser(false, true);

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('username', 'username')
            ->type('email@example.com', 'email')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
            ->press('Toevoegen')
            ->see('Het sub account is aangemaakt');

        $this->assertTrue(\Auth::validate(['username' => 'username', 'company_id' => 12345, 'password' => 'password']));
    }

    public function testDuplicateSubAccountCreation()
    {
        $this->createCompany();
        $user = $this->createUser(false, true);

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('username', 'username')
            ->type('email@example.com', 'email')
            ->type('password', 'password')
            ->type('password', 'password_confirmation')
            ->press('Toevoegen')
            ->see('Het sub account is aangemaakt');

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('username', 'username')
            ->type('email2@example.com', 'email')
            ->type('another_password', 'password')
            ->type('another_password', 'password_confirmation')
            ->press('Toevoegen')
            ->see('Er bestaat al een sub account met deze login naam');

        $this->assertTrue(\Auth::validate(['username' => 'username', 'company_id' => 12345, 'password' => 'password']));
        $this->assertFalse(\Auth::validate(['username' => 'username', 'company_id' => 12345, 'password' => 'another_password']));
    }
}
