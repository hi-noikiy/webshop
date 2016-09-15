<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubAccountTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfManagerHasSubAccountsNavigationItem()
    {
        $this->createCompany();
        $user = $this->createUser(false, true);

        $this->actingAs($user)
            ->visit('account')
            ->see('Sub-Accounts');
    }

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

    public function testIfManagerCanDeleteSubAccount()
    {
        $this->createCompany();

        // Manager
        $user = $this->createUser();

        // Sub account
        $this->createUser(false, false, '54321');

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('54321', 'username')
            ->type('1', 'delete')
            ->press('Verwijderen')
            ->see('Het sub account is verwijderd');
    }

    public function testIfManagerCanNotDeleteSubAccountFromOtherCompany()
    {
        $this->createCompany();

        // Manager
        $user = $this->createUser();

        // Sub account
        $this->createUser(false, false, '54321', '54321');

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('54321', 'username')
            ->type('1', 'delete')
            ->press('Verwijderen')
            ->see('Geen sub account gevonden die bij uw account hoort');
    }

    public function testIfManagerCanNotDeleteOwnAccount()
    {
        $this->createCompany();

        // Main account
        $this->createUser();

        // Manager
        $user = $this->createUser(false, true, '54321');

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('54321', 'username')
            ->type('1', 'delete')
            ->press('Verwijderen')
            ->see('U kunt uw eigen account niet verwijderen');
    }

    public function testIfManagerCanNotDeleteMainAccount()
    {
        $this->createCompany();

        // Main account
        $this->createUser();

        // Manager
        $user = $this->createUser(false, true, '54321');

        $this->actingAs($user)
            ->visit('account/accounts')
            ->type('12345', 'username')
            ->type('1', 'delete')
            ->press('Verwijderen')
            ->see('U kunt het hoofdaccount niet verwijderen');
    }
}
