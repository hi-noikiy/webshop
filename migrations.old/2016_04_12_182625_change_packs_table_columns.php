<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePacksTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packs', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'image'
            ]);
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->integer('product_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packs', function (Blueprint $table) {
            $table->dropColumn('product_number');
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('image')->nullable();
        });
    }
}
