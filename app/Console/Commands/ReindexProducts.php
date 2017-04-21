<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ReindexProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-create the product index';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Elasticsearch client
        $client = app('elastic');

        // Index name
        $index = 'products_' . date('YmdHis');

        // All products
        $products = Product::all([
            'id',
            'name',
            'sku',
            'group',
            'alternate_sku',
            'ean',
            'brand',
            'series',
            'type',
            'keywords'
        ]);

        // Progress bar
        $bar = $this->output->createProgressBar(count($products));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s%');

        // Counter
        $count = 0;

        // Create the new index
        $client->indices()->create([
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    '_default_' => [
                        'properties' => [
                            'brand' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                            'series' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                            'type' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // Reset the parameters
        $params = [];

        // Loop through each product
        foreach ($products as $product) {
            $count++;

            // Send the products to elastic every 1000 products
            if ($count % 1000 === 0) {
                try {
                    $client->bulk($params);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());

                    return 1;
                }

                // Clear the list for the next bulk
                $params = [
                    'body' => []
                ];
            }

            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => 'product',
                    '_id' => $product->id
                ]
            ];

            $array = array_except($product->toArray(), [
                'id',
                'sku',
                'group'
            ]);

            $array['sku'] = (string) $product->number;
            $array['group'] = (string) $product->group;

            $params['body'][] = $array;

            $bar->advance();
        }

        if (!empty($params['body'])) {
            try {
                $client->bulk($params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());

                return 1;
            }
        }

        // Create an alias for the new index
        $client->indices()->putAlias([
            'index' => $index,
            'name' => 'products'
        ]);

        // Get the list of aliases
        $aliases = $client->indices()->getAliases();

        // Delete all the aliases that do not match the one that we just created
        foreach ($aliases as $alias => $value) {
            if ($alias !== $index) {
                $client->indices()->delete([
                    'index' => $alias
                ]);
            }
        }


        $bar->finish();

        $this->info("\n\n".$count.' products have been indexed.'."\n");
    }
}