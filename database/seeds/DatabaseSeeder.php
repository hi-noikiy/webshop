<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!app()->environment('production')) {
            $this->call(WTG\Customer\Seeders\CompaniesTableSeeder::class);
            $this->call(WTG\Customer\Seeders\CustomersTableSeeder::class);
            $this->call(WTG\Catalog\Seeders\ProductsTableSeeder::class);
        }
    }
}
