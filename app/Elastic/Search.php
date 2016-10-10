<?php

namespace App\Elastic;

use Elasticsearch\ClientBuilder;
use App\Elastic\Product;

class Search
{

    /**
     * Perform a text search for products
     *
     * @param  string  $query
     * @return array
     */
    public static function text($query)
    {
        $client = app('elastic');

        $filter = [];

        if (request()->has('brand')) {
            $filter[]['match']['brand'] = request('brand');
        }

        if (request()->has('serie')) {
            $filter[]['match']['series'] = request('serie');
        }

        if (request()->has('type')) {
            $filter[]['match']['type'] = request('type');
        }

        $queryFields = [
            'name',
            'keywords',
            'ean',
            'altNumber',
            'number',
            'group'
        ];

        $results = $client->search([
            'index' => 'wiringa_local',
            'type' => 'product',
            'body' => [
                'from' => 0,
                'size' => 20000,
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'fields' => $queryFields,
                                'query' => $query,
                                'fuzziness' => 'AUTO'
                            ]
                        ],
                        'filter' => $filter
                    ]
                ]
            ],
        ]);

        $_productCollection = collect($results['hits']['hits']);

//        dd($_productCollection);

        $products = $_productCollection->map(function($item, $key) {
            return new Product($item['_source']);
        });

        return [
            'products' => $products,
            'total' => $products->count()
        ];
    }

}