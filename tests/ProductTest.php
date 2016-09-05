<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the product page with an existing product
     */
    public function testExistingProduct()
    {
        $this->createDiscount();
        $this->createProduct();

        $this->visit('/product/9999999')
            ->see('Someproduct');
    }

    /**
     * Test the product page with a missing product
     */
    public function testMissingProduct()
    {
        $this->get('/product/9999999')
            ->assertResponseStatus(404);
    }

}
