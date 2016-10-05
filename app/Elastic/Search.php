<?php

namespace App\Elastic;

use Elasticsearch\ClientBuilder;
use App\Elastic\Product;
//use App\Product;

class Search
{

    public static function text($query)
    {
        $qs = microtime(true);

        $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();

        $filter = [];

        if (request()->has('brand')) {
            $filter['term']['brand'] = request('brand');
        }

        if (request()->has('series')) {
            $filter['term']['series'] = request('series');
        }

        if (request()->has('type')) {
            $filter['term']['type'] = request('type');
        }

        $boostFields = [
            'name',
            'keywords'
        ];

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
                            0 => [
                                'multi_match' => [
                                    'fields' => $queryFields,
                                    'query' => $query,
                                    'fuzziness' => 'AUTO',
                                    'boost' => 2.0
                                ],
                            ],
                            0 => [
                                'multi_match' => [
                                    'fields' => $boostFields,
                                    'query' => $query,
                                    'boost' => 0.5
                                ]
                            ]
                        ],
                        'filter' => $filter
                    ]
                ]
            ],
        ]);

        $_productCollection = collect($results['hits']['hits']);

        $_productCollection = $_productCollection->map(function($item, $key) {
            return new Product($item['_source']);
        });

        dump(microtime(true) - $qs);
        dd($_productCollection->first()->price);

        $results = $results['hits'];

        if (count($results['hits']) === 0) {
            $products = collect();
        } else {
            $ids = collect(array_pluck($results['hits'], '_id'));

//            $products = Product::select([
//                'id',
//                'name',
//                'number',
//                'group',
//                'price',
//                'image',
//                'brand',
//                'series',
//                'type',
//                'special_price',
//                'action_type',
//                'refactor',
//                'price_per'
//            ])->whereIn('id', $ids)
//                ->get();

            $products = $ids->map(function ($id, $key) use ($products) {
                return $products->filter(function ($item) use ($id) {
                    return $item->id == (int) $id;
                })->first();
            });
        }

        return [
            'products' => $products,
            'total' => $results['total']
        ];
    }

}