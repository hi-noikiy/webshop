<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeparateOrderProductsFromOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('company_login', 10);
            $table->integer('product_number');
            $table->string('name');
            $table->string('qty');
            $table->double('price', 10, 2);
            $table->string('discount');
            $table->double('subtotal', 10, 2);
            $table->timestamps();
        });

        \App\Models\Order::chunk(50, function ($orders) {
            $orders->each(function ($order) {
                $products = unserialize($order->products);

                foreach ($products as $product) {
                    $orderProduct = new \App\Models\OrderProduct;

                    $orderProduct->order_id = $order->id;
                    $orderProduct->company_login = $order->User_id;
                    $orderProduct->product_number = $product['id'];
                    $orderProduct->name = $product['name'];
                    $orderProduct->qty = $product['qty'];
                    $orderProduct->price = $product['price'] ?? 0;
                    $orderProduct->discount = $product['options']['korting'] ?? 0;
                    $orderProduct->subtotal = $product['subtotal'] ?? 0;

                    $orderProduct->save();
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
