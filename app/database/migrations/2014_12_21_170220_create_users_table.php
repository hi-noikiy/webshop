<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id'); 				// Autoincrement	: ID
			$table->integer('login')->unique(); 	// INTEGER			: Username
			$table->string('company', 255);			// VARCHAR(255)		: Company Name
			$table->string('street', 100);			// VARCHAR(100)		: Street + Nr
			$table->string('postcode', 7); 			// VARCHAR(7)		: Postcode: XXXX YY
			$table->string('city', 50);				// VARCHAR(50)		: City
			$table->string('email', 50); 			// VARCHAR(50)		: Email address
			$table->boolean('active');				// BOOLEAN			: Active (if this is true, the user is able to login)
			$table->string('password');				// VARCHAR()		: Password
			$table->mediumText('favorites');		// MEDIUMTEXT		: A serialized array containing the favorite products from the user
			$table->mediumText('cart');				// MEDIUMTEXT		: The cart is saved here between sessions
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
		Schema::drop('users');
	}

}
