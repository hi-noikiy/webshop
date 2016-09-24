<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class checkRelatedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:related_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if all related products still exist';

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
        $errors = '';
        $products = Product::select(['number', 'related_products'])->where('related_products', '!=', '')->get();
        $bar = $this->output->createProgressBar(count($products));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s% %product%');

        // Loop through every product that has related products
        foreach ($products as $product) {
            $bar->setMessage($product->number, 'product');

            // Loop though the related products to check if they exist
            foreach (explode(',', $product->related_products) as $related_product) {
                // Throw an error in the user's face if the related product does not exist
                if (Product::where('number', $related_product)->count() === 0) {
                    $errors .= 'Product '.$product->number.' has a non-existing related product: '.$related_product."\r\n";
                }
            }

            $bar->advance();
        }

        $bar->finish();

        $this->line("\r\n");

        if ($errors !== '') {
            $this->error("\r\n \r\n".$errors);
        } else {
            $this->info("\r\nEr zijn geen fouten gevonden");
        }

        $this->line("\r\n");
    }
}
