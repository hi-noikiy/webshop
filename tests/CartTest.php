<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

/**
 * Class CartTest
 */
class CartTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create a product for testing
     *
     * @return $this
     */
    private function createProduct()
    {
        $contents = 'Someproduct;NE;BDY;1000030;10900100;20020658;1;A;Stk;Stk;1;HG;1;Dyka;8716936000541;zw0042005.jpg;1;;0,02;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';
        $data = str_getcsv($contents, ';');

        DB::table('products')->insert([
            'name'             => $data[0],
            'number'           => $data[3],
            'group'            => $data[4],
            'altNumber'        => $data[5],
            'stockCode'        => $data[7],
            'registered_per'   => $data[8],
            'packed_per'       => $data[9],
            'price_per'        => $data[10],
            'refactor'         => preg_replace("/\,/", ".", $data[12]),
            'supplier'         => $data[13],
            'ean'              => $data[14],
            'image'            => $data[15],
            'length'           => $data[17],
            'price'            => $data[18],
            'vat'              => $data[20],
            'brand'            => $data[21],
            'series'           => $data[22],
            'type'             => $data[23],
            'special_price'    => ($data[24] === "" ? "0.00" : preg_replace("/\,/", ".", $data[24])),
            'action_type'      => $data[25],
            'keywords'         => $data[26],
            'related_products' => $data[27],
            'catalog_group'    => $data[28],
            'catalog_index'    => $data[29],
        ]);

        return $this;
    }

    /**
     * Create a product for testing
     *
     * @return $this
     */
    private function createDiscount()
    {
        DB::table('discounts')->insert([
            'table'         => 'VA-220',
            'User_id'       => 10000,
            'product'       => 10900100,
            'start_date'    => '1-1-2008 0:00:00',
            'end_date'      => '31-12-9999 0:00:00',
            'discount'      => '0',
            'group_desc'    => 'Onderdelen',
            'product_desc'  => '',
        ]);

        return $this;
    }

    /**
     * Create a fake user
     *
     * @return $this
     */
    private function createFakeUser()
    {
        DB::table('users')->insert([
            'login'     => 10000,
            'company'   => 'company',
            'street'    => 'Test drv. 69',
            'postcode'  => '1337 GG',
            'city'      => 'somewhere',
            'email'     => 'test@example.com',
            'active'    => '1',
            'isAdmin'   => '0',
            'password'  => bcrypt('test')
        ]);

        return $this;
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAddProductToCart()
    {
        $this->createProduct()
            ->createDiscount()
            ->createFakeUser();

        $this->actingAs(User::where('login', 10000)->first())
            ->visit('/product/1000030')
            ->dontSee('Not Found')
            ->see('Someproduct')
            ->type(5, 'qty')
            ->press('Toevoegen')
            ->see('Verder winkelen');
    }
    
}
