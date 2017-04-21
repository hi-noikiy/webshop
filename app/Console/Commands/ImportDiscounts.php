<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ImportDiscounts
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ImportDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:discounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the discounts file and import the new discounts';

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
        \Import::importer('discount')->run();
    }
}
