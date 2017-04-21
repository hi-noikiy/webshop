<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedColumnsFromCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'favorites',
                'cart',
                'remember_token',
                'isAdmin'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('password')->nullable();
            $table->string('favorites', 8000)->default('a:0:{}');
            $table->mediumText('cart')->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->rememberToken();
        });
    }
}
