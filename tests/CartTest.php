<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

/**
 * Class CartTest
 */
class CartTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddProductToCart()
    {
        $this->createProduct();
        $this->createDiscount();
        $user = $this->createUser();

        $this->actingAs($user)
            ->visit('/product/9999999')
            ->dontSee('Not Found')
            ->see('Someproduct')
            ->type(5, 'qty')
            ->press('Toevoegen')
            ->see('Verder winkelen');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddProductToCartWithZeroQty()
    {
        $this->createProduct();
        $this->createDiscount();
        $user = $this->createUser();

        $this->actingAs($user)
            ->visit('/product/9999999')
            ->dontSee('Not Found')
            ->see('Someproduct')
            ->type(0, 'qty')
            ->press('Toevoegen')
            ->see('Aantal dient minimaal 1 te zijn');
    }
    
}
