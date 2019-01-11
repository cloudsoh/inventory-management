<?php
use Faker\Generator as Faker;

$factory->define(\CloudSoh\InventoryManagement\InventoryCategory::class, function (Faker $faker) {
    $categories = ['Beans', 'Drinks', 'Vegetables', 'Meats', 'Beers', 'Fruits', 'Seasonings'];

    return [
        'name' => $categories[array_rand($categories)],
    ];
});
