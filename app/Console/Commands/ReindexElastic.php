<?php

namespace App\Console\Commands;

use App\Product;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class ReindexElastic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:reindex { index : The name of the index }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-create indices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('index')) {
            // Elasticsearch client
            $client = app('elastic');

            // Index name
            $index = $this->argument('index') . '_' . date('YmdHis');

            // All products
            $products = Product::all([
                'id',
                'name',
                'number',
                'group',
                'altNumber',
                'ean',
                'brand',
                'series',
                'type',
                'keywords'
            ]);

            // Progress bar
            $bar = $this->output->createProgressBar(count($products));
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s%');

            // Parameters
            $params = [];

            // Counter
            $count = 0;

            // Create the new index
            $client->indices()->create([
                'index' => $index,
                'body' => [
                    'settings' => [
                        'number_of_shards' => 5,
                        'number_of_replicas' => 1
                    ]
                ]
            ]);

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
                    'number',
                    'group'
                ]);

                $array['number'] = (string) $product->number;
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
                'name' => $this->argument('index')
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
}
