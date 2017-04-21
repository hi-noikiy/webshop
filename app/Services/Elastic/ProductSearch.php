<?php

namespace App\Services\Elastic;

use App\Services\Elastic\Objects\Product;
use Illuminate\Support\Collection;

class ProductSearch
{
    /**
     * @var string
     */
    protected $query;

    /**
     * @var array
     */
    protected $data = [
        'index' => 'products',
        'type' => 'product',
        'body' => [
            'from' => 0,
            'size' => 20000,
            'query' => [
                'bool' => []
            ],
            "aggregations" => [
                "brands" => [
                    "terms" => ["field" => "brand", "size" => 0]
                ],
                "series" => [
                    "terms" => ["field" => "series", "size" => 0]
                ],
                "types" => [
                    "terms" => ["field" => "type", "size" => 0]
                ]
            ]
        ],
    ];

    /**
     * Unfiltered (query only) response
     *
     * @var array
     */
    protected $queryResponse = null;

    /**
     * Filtered response
     *
     * @var array
     */
    protected $filteredResponse = null;

    /**
     * @var Collection
     */
    protected $items = null;

    /**
     * @var int
     */
    protected $total = null;

    /**
     * @var array
     */
    protected $filters = null;

    /**
     * @var array
     */
    protected $terms = [];

    /**
     * @var array
     */
    protected $suggestions = null;

    /**
     * ProductSearch constructor.
     *
     * @param  null  $query
     * @param  array  $terms
     */
    public function __construct($query, $terms = [])
    {
        $this->client = app()->make('elastic');
        $this->query = $query;
        $this->terms = $terms;

        $this->data['body']['suggest'];

        if ($query) {
            $this->data['body']['suggest'] = [
                "text" => $query,
                "name-suggest" => [
                    "term" => [
                        "field" => "name"
                    ]
                ]
            ];

            $this->data['body']['query']['bool']['must'] = [
                'query_string' => [
                    'query' => $query
                ],
            ];
        }
    }

    /**
     * Get the raw response from ES
     *
     * @return array
     */
    public function getFilteredResponse()
    {
        if ($this->filteredResponse === null) {
            $data = $this->data;

            if (count($this->terms)) {
                foreach ($this->terms as $field => $term) {
                    if ($term === null) {
                        continue;
                    }

                    $data['body']['query']['bool']['filter'][]['term'][$field] = $term;
                }
            }

            $this->filteredResponse = $this->client->search($data);
        }

        return $this->filteredResponse;
    }

    /**
     * Get the raw response from ES
     *
     * @return array
     */
    public function getQueryResponse()
    {
        if ($this->queryResponse === null) {
            $this->queryResponse = $this->client->search($this->data);
        }

        return $this->queryResponse;
    }

    /**
     * Get the items from the response
     *
     * @return array
     */
    public function getItems()
    {
        if ($this->items === null) {
            $_productCollection = collect($this->getFilteredResponse()['hits']['hits']);

            $this->items = $_productCollection->map(function($item, $key) {
                return new Product($item['_source']);
            });
        }

        return $this->items;
    }

    /**
     * Get the total amount of items
     *
     * @return int
     */
    public function getTotal()
    {
        if ($this->total === null) {
            $this->total = $this->getFilteredResponse()['hits']['total'];
        }

        return $this->total;
    }

    /**
     * Get the filters
     *
     * @return Collection
     */
    public function getFilters()
    {
        if ($this->filters === null) {
            $aggregations = $this->getFilteredResponse()['aggregations'];

            foreach ($aggregations as $filter => $aggregation) {
                $this->filters[$filter] = collect($aggregation['buckets'])->sortBy('key');
            }
        }

        return collect($this->filters);
    }

    /**
     * @return array
     */
    public function getSuggestions()
    {
        if ($this->suggestions === null) {
            $suggests = $this->getFilteredResponse()['suggest'];

            foreach ($suggests as $suggest) {
                foreach ($suggest as $suggestion) {
                    foreach ($suggestion['options'] as $option) {
                        $this->suggestions[] = $option['text'];
                    }
                }
            }
        }

        return $this->suggestions;
    }
}