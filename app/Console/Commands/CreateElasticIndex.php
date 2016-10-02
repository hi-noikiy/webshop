<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;

class CreateElasticIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:create-index {index : The index name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an index for Elasticsearch';

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
            $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();

            $params = [
                'index' => $this->argument('index')
            ];

            try {
                $client->indices()->create($params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());

                return 1;
            }
        }
    }
}
