<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class DeleteElasticIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:drop-index { index : The name of the index }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop an index from elastic';

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
                $client->indices()->delete($params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());

                return 1;
            }
        }
    }
}
