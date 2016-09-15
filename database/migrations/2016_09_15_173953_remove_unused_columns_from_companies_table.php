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
            $table->dropColumn('street');
            $table->dropColumn('postcode');
            $table->dropColumn('city');
            $table->dropColumn('email');
            $table->dropColumn('password');
            $table->dropColumn('favorites');
            $table->dropColumn('cart');
            $table->dropColumn('remember_token');
            $table->dropColumn('isAdmin');
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
            $table->string('street', 100);
            $table->string('postcode', 7);
            $table->string('city', 50);
            $table->string('email', 50);
            $table->string('password');
            $table->string('favorites', 8000)->default('a:0:{}');
            $table->mediumText('cart')->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->rememberToken();
        });
    }
}
