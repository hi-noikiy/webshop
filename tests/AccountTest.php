<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfUserCanChangePasswordWithCorrectCredentials()
    {
        $this->createCompany();
        $user = $this->createUser();

        $this->actingAs($user)
            ->visit(route('change_password'))
            ->type('password', 'password_old')
            ->type('new_password', 'password')
            ->type('new_password', 'password_confirmation')
            ->press('Wijzigen')
            ->see('Uw wachtwoord is gewijzigd');

        $this->assertTrue(\Auth::validate(['username' => '12345', 'company_id' => 12345, 'password' => 'new_password']));
    }

    public function testIfUserCanChangePasswordWithNonMatchingPasswords()
    {
        $this->createCompany();
        $user = $this->createUser();

        $this->actingAs($user)
            ->visit(route('change_password'))
            ->type('password', 'password_old')
            ->type('new_password', 'password')
            ->type('new_wrong_password', 'password_confirmation')
            ->press('Wijzigen')
            ->see('Wachtwoord en Wachtwoord bevestiging komen niet overeen');

        $this->assertFalse(\Auth::validate(['username' => '12345', 'company_id' => 12345, 'password' => 'new_password']));
    }

    public function testIfUserCanChangePasswordWithWrongCredentials()
    {
        $this->createCompany();
        $user = $this->createUser();

        $this->actingAs($user)
            ->visit(route('change_password'))
            ->type('wrong_password', 'password_old')
            ->type('new_password', 'password')
            ->type('new_password', 'password_confirmation')
            ->press('Wijzigen')
            ->see('Het oude wachtwoord en uw huidige wachtwoord komen niet overeen');

        $this->assertFalse(\Auth::validate(['username' => '12345', 'company_id' => 12345, 'password' => 'new_password']));
    }

}
