<?php

use App\Company;
use App\User;
use App\Product;
use App\Description;
use App\Discount;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Create a test company.
     *
     * @param array $params
     *
     * @return App\Company
     */
    protected function createCompany($params = [])
    {
        $company = new Company();

        $company->login = isset($params['login']) ? $params['login'] : '12345';
        $company->company = isset($params['name']) ? $params['name'] : 'Test Company';
        $company->active = isset($params['active']) ? $params['active'] : true;

        $company->save();

        return $company;
    }

    /**
     * Create a test user.
     *
     * @param bool   $admin
     * @param bool   $manager
     * @param string $username
     * @param string $company
     *
     * @return App\User
     */
    protected function createUser($admin = false, $manager = true, $username = '12345', $company = '12345')
    {
        $user = new User();

        $user->username = $username;
        $user->company_id = $company;
        $user->email = 'Test@example.com';
        $user->manager = (int) $manager;
        $user->isAdmin = (int) $admin;
        $user->password = bcrypt('password');

        $user->save();

        return $user;
    }

    /**
     * Create some content to test with.
     */
    protected function createContent()
    {
        DB::table('text')->insert([
            'name'      => 'test.page',
            'title'     => 'Test page',
            'page'      => 'Test',
            'content'   => 'This is some freaking awesome content!',
        ]);
    }

    /**
     * Create a product for testing.
     *
     * @return $this
     */
    protected function createProduct()
    {
        $contents = 'Someproduct;NE;BDY;9999999;10900100;20020658;1;A;Stk;Stk;1;HG;1;Dyka;8716936000541;zw0042005.jpg;1;;0,02;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';
        $data = str_getcsv($contents, ';');

        return Product::create([
            'name'             => $data[0],
            'number'           => $data[3],
            'group'            => $data[4],
            'altNumber'        => $data[5],
            'stockCode'        => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => preg_replace("/\,/", '.', $data[12]),
            'supplier'         => $data[13],
            'ean'              => $data[14],
            'image'            => $data[15],
            'length'           => $data[17],
            'price'            => $data[18],
            'vat'              => $data[20],
            'brand'            => $data[21],
            'series'           => $data[22],
            'type'             => $data[23],
            'special_price'    => ($data[24] === '' ? '0.00' : preg_replace("/\,/", '.', $data[24])),
            'action_type'      => $data[25],
            'keywords'         => $data[26],
            'related_products' => $data[27],
            'catalog_group'    => $data[28],
            'catalog_index'    => $data[29],
        ]);
    }

    /**
     * Create a product for testing.
     *
     * @return $this
     */
    protected function createDiscount()
    {
        return Discount::create([
            'table'         => 'VA-220',
            'User_id'       => 12345,
            'product'       => 9999999,
            'start_date'    => '1-1-2008 0:00:00',
            'end_date'      => '31-12-9999 0:00:00',
            'discount'      => '0',
            'group_desc'    => 'Onderdelen',
            'product_desc'  => '',
        ]);
    }

    protected function createDescription()
    {
        $d = new Description;

        $d->product_id = 9999999;
        $d->value = 'This is a test description';

        $d->save();
    }
}
