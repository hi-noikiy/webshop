<?php

namespace App\Elastic;

class Indexer
{

    /**
     * @var string
     */
    private $index;

    public function __construct($index)
    {
        $this->index = $index;
    }

}