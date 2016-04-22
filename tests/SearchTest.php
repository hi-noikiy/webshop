<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Product;

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
        $this->createTestProduct();

        $this->visit('/webshop')
            ->type('test', 'q')
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

    private function createTestProduct()
    {
        return Product::create([
            'name'             => 'Test product',
            'number'           => 1234567,
            'group'            => 123456,
            'altNumber'        => '',
            'stockCode'        => 'A',
            'registered_per'   => 'stk',
            'packed_per'       => 'stk',
            'price_per'        => 1,
            'refactor'         => 1,
            'supplier'         => 'none',
            'ean'              => 122,
            'image'            => 'none.jpg',
            'length'           => '12m',
            'price'            => '1.00',
            'vat'              => '32',
            'brand'            => 'brand',
            'series'           => 'serie',
            'type'             => 'type',
            'special_price'    => '0.00',
            'action_type'      => '',
            'keywords'         => '',
            'related_products' => '',
            'catalog_group'    => '',
            'catalog_index'    => '',
        ]);
    }
}
