<?php

namespace Tests\Feature\Services;

use Tests\Feature\TestCase;

class HelperServiceTest extends TestCase
{
    /**
     * @test
     */
    public function stockCodeReturnsCorrectValues()
    {
        $this->assertEquals("Uit voorraad", app('helper')->stockCode("A"));
        $this->assertEquals("Binnen 24/48 uur mits voor 16.00 besteld", app('helper')->stockCode("B"));
        $this->assertEquals("In overleg", app('helper')->stockCode("C"));
        $this->assertEquals("Uitlopend", app('helper')->stockCode("D"));
        $this->assertEquals("X", app('helper')->stockCode("X"));
    }
}