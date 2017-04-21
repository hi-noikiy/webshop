<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportStatusColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'created_at',
                'updated_at'
            ]);
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'created_at',
                'updated_at'
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('hash')->default('');
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->string('hash')->default('');
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'hash',
                'imported_at'
            ]);
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'hash',
                'imported_at'
            ]);
        });
    }
}
