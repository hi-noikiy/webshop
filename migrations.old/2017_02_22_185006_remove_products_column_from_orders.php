<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductsColumnFromOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $orders = \App\Models\Order::all();

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'products',
                'created_at',
                'updated_at'
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamps();
        });

        $orders->each(function ($order) {
            \App\Models\Order::find($order->id)->fill([
                'created_at' => $order->created_at
            ])->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('products')->nullable();
        });
    }
}
