<?php

use App\Models\User;
use App\Models\Favorite;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('product_number');
        });

        User::chunk(50, function ($users) {
            $users->each(function ($user) {
                $favorites = unserialize($user->favorites);

                foreach ($favorites as $favorite) {
                    $model = new Favorite;
                    $model->user_id = $user->id;
                    $model->product_number = $favorite;
                    $model->save();
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
        Schema::dropIfExists('favorites');
    }
}
