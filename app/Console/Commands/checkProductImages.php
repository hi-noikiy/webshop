<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class checkProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:product_images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if all the product images have been uploaded';

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
        $errors = [];
        $products = Product::select(['number', 'image'])->distinct()->get();
        $bar = $this->output->createProgressBar(count($products));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s% %product%');

        // Loop through every product that has related products
        foreach ($products as $product) {
            $bar->setMessage($product->number, 'product');

            if (!\File::exists(public_path().'/img/products/'.$product->image)) {
                $errors[] = ['Product number' => $product->number, 'Image' => $product->image];
            }

            $bar->advance();
        }

        $bar->finish();

        $this->line("\r\n");

        if (count($errors) === 0) {
            $this->info("\r\nAlle afbeeldingen zijn geuploaded");
        } else {
            $this->table(['Product number', 'Image'], $errors);
        }

        $this->line("\r\n");
    }
}
