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
}
