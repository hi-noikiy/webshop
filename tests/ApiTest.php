<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use WithoutMiddleware,
        DatabaseTransactions;


    /**
     * Test the product method with an existing product
     *
     * @return void
     */
    public function testProductMethodWithExistingProduct()
    {
        $this->createProduct();

        $this->get('/admin/api/product/9999999')
            ->seeJson([
                'message' => 'Details for product: 9999999',
                'status' => 'success'
            ])
            ->assertResponseStatus(200);
    }

    /**
     * Test the product method with a non-existing product
     *
     * @return void
     */
    public function testProductMethodWithNonExistingProduct()
    {
        $this->get('/admin/api/product/9999999')
            ->seeJson([
                'message' => 'No details for product: 9999999',
                'status' => 'failure'
            ])
            ->assertResponseStatus(404);
    }

    /**
     * Test the content method with an existing page
     *
     * @return void
     */
    public function testContentMethodWithExistingPage()
    {
        $this->createContent();

        $this->get('/admin/api/content?page=test.page')
            ->seeJson([
                'message' => 'Content for page test.page',
            ])
            ->assertResponseStatus(200);
    }

    /**
     * Test the content method with a non-existing page
     *
     * @return void
     */
    public function testContentMethodWithNonExistingPage()
    {
        $this->get('/admin/api/content?page=test.page')
            ->seeJson([
                'message' => 'No content found for page: test.page',
            ])
            ->assertResponseStatus(404);
    }

    /**
     * Test the content method with a non-existing page
     *
     * @return void
     */
    public function testContentMethodWithMissingParameter()
    {
        $this->get('/admin/api/content')
            ->seeJson([
                'message' => 'Missing request parameter: `page`',
            ])
            ->assertResponseStatus(400);
    }

    /**
     * Create some content to test with
     */
    private function createContent()
    {
        DB::table('text')->insert([
            'name'      => 'test.page',
            'title'     => 'Test page',
            'page'      => 'Test',
            'content'   => 'This is some freaking awesome content!'
        ]);
    }

    /**
     * Create a product for testing
     *
     * @return $this
     */
    private function createProduct()
    {
        $contents = 'Someproduct;NE;BDY;9999999;10900100;20020658;1;A;Stk;Stk;1;HG;1;Dyka;8716936000541;zw0042005.jpg;1;;0,02;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';
        $data = str_getcsv($contents, ';');

        DB::table('products')->insert([
            'name'             => $data[0],
            'number'           => $data[3],
            'group'            => $data[4],
            'altNumber'        => $data[5],
            'stockCode'        => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => preg_replace("/\,/", ".", $data[12]),
            'supplier'         => $data[13],
            'ean'              => $data[14],
            'image'            => $data[15],
            'length'           => $data[17],
            'price'            => $data[18],
            'vat'              => $data[20],
            'brand'            => $data[21],
            'series'           => $data[22],
            'type'             => $data[23],
            'special_price'    => ($data[24] === "" ? "0.00" : preg_replace("/\,/", ".", $data[24])),
            'action_type'      => $data[25],
            'keywords'         => $data[26],
            'related_products' => $data[27],
            'catalog_group'    => $data[28],
            'catalog_index'    => $data[29],
        ]);

        return $this;
    }
}
