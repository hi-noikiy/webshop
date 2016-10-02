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
            $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();

            // Index name
            $index = $this->argument('index') . '_' . date('YmdHis');

            // All products
            $products = Product::all()->toArray();

            // Progress bar
            $bar = $this->output->createProgressBar(count($products));
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s%');

            // Parameters
            $params = [];

            // Counter
            $count = 1;

            // Create the new index
            $client->indices()->create([
                'index' => $index
            ]);

            // Loop through each product
            foreach ($products as $product) {
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
                        '_id' => $product['number']
                    ]
                ];

                $params['body'][] = $product;

                $bar->advance();
                $count++;
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
        }
    }
}
