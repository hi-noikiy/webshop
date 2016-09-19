<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DescriptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testIfProductDisplaysDescriptionBlock()
    {
        $this->createProduct();
        $this->createDescription();

        $this->visit('product/9999999')
            ->see('This is a test description');
    }
}
