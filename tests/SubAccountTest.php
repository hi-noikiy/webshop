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
        $this->createUser(false, true);

        $this->actingAs(User::whereUsername('12345')->first())
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
        $this->createUser(false, false);

        $this->actingAs(User::whereUsername('12345')->first())
            ->visit('account')
            ->dontSee('Sub-Accounts');
    }
}
