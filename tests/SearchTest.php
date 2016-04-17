<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearchForExistingProduct()
    {
        $this->visit('/webshop')
            ->type('buis', 'q')
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
            ->type('341ktm13l4', 'q')
            ->press('Zoeken')
            ->see('Geen resultaten gevonden');
    }
}
