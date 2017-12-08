<?php

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;

/**
 * Import products command.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Products extends Command
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
     * Products constructor.
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