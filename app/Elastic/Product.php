<?php

namespace App\Elastic;

use App\Product as EloquentProduct;

class Product
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $group;

    /**
     * @var int
     */
    private $alt_number;

    /**
     * @var int
     */
    private $ean;

    /**
     * @var string
     */
    private $brand;

    /**
     * @var string
     */
    private $series;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $keywords;

    /**
     * @var \App\Product
     */
    private $model;

    /**
     * Product constructor.
     *
     * @param  array  $params
     */
    public function __construct(array $params)
    {
        $this->name = $params['name'];
        $this->number = $params['number'];
        $this->group = $params['group'];
        $this->alt_number = $params['altNumber'];
        $this->ean = $params['ean'];
        $this->brand = $params['brand'];
        $this->series = $params['series'];
        $this->type = $params['type'];
        $this->keywords = $params['keywords'];
    }

    /**
     * Get the Eloquent model
     *
     * @return \App\Product
     */
    public function getModel()
    {
        if (!isset($this->model)) {
            $this->model = EloquentProduct::findByNumber($this->number);
        }

        return $this->model;
    }

    public function __get($name)
    {
        if ($name === 'model') {
            $this->getModel();
        }

        return $this->$name ?? $this->getModel()->$name;
    }

}