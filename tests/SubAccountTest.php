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
        $this->createUser(true);

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
        $this->createUser(false);

        $this->actingAs(User::whereUsername('12345')->first())
            ->visit('account')
            ->dontSee('Sub-Accounts');
    }

    /**
     * Create a test company
     */
    private function createCompany()
    {
        $company = new Company;

        $company->login = '12345';
        $company->street = 'Teststraat 23';
        $company->postcode = '1234 XX';
        $company->city = 'City';
        $company->email = 'Test@example.com';
        $company->active = true;

        $company->save();
    }

    /**
     * Create a test user
     *
     * @param bool $manager
     */
    private function createUser($manager = false)
    {
        $company = new User;

        $company->username = '12345';
        $company->company_id = '12345';
        $company->email = 'Test@example.com';
        $company->active = true;
        $company->manager = $manager;
        $company->password = bcrypt('password');

        $company->save();
    }
}
