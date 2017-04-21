<?php

namespace App\Services\Elastic\Objects;

use App\Models\Product as EloquentProduct;

class Product
{
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

    /**
     * If the attribute is not found, get it from the Eloquent model
     *
     * @param string  $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'model') {
            $this->getModel();
        }

        try {
            return $this->$name ?? $this->getModel()->$name;
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), $e->getTrace());

            return null;
        }
    }

    /**
     * Forward function calls to the model
     *
     * @param  string  $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($name !== 'getModel') {
            try {
                return call_user_func_array([$this->getModel(), $name], $arguments);
            } catch (\Exception $e) {
                \Log::error($e->getMessage(), $e->getTrace());

                return null;
            }
        }
    }
}