<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearchForExistingProduct()
    {
        $this->createProduct();

        $this->visit('/webshop')
            ->type('Someproduct', 'q')
            ->press('Zoeken')
            ->see('resultaten gevonden in');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearchForNonExistingProduct()
    {
        $this->visit('/webshop')
            ->type('nonexistantproduct', 'q')
            ->press('Zoeken')
            ->see('Geen resultaten gevonden');
    }
}
