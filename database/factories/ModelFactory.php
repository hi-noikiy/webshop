<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\WTG\Catalog\Models\Product::class, function (Faker\Generator $faker) {
    static $special_price = [10.00, 50.00, 100.99];
    static $action_types = ['clearance', 'action', null, null, null, null]; // Lower chance of getting a non-null value
    $action_type = $action_types[array_rand($action_types)];

    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'stock_code' => 0,
        'sku' => $faker->numerify('#######'),
        'group' => $faker->numerify('########'),
        'alternate_sku' => $faker->numerify('##########'),
        'registered_per' => "stk",
        'packed_per' => "stk",
        'price_per' => "stk",
        'refactor' => 1,
        'ean' => $faker->numberBetween(),
        'image' => $faker->imageUrl(200,200),
        'brand' => $faker->colorName,
        'series' => $faker->colorName,
        'type' => $faker->colorName,
        'price' => 1000.00,
        'action_type' => $action_type,
        'special_price' => ($action_type ? $special_price[array_rand($special_price)] : null),
        'keywords' => '',
        'related_products' => '',
        'catalog_group' => '',
        'catalog_index' => ''
    ];
});
