<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ImportProducts
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the products table';

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
     * @return void
     */
    public function handle()
    {
        \Import::importer('product')->run();
    }
}
