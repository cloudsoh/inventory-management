<?php

use Faker\Generator as Faker;
use CloudSoh\InventoryManagement\Inventory;
use CloudSoh\InventoryManagement\Metric;
use CloudSoh\InventoryManagement\InventoryCategory;

$factory->define(Inventory::class, function (Faker $faker) {
    $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
    return [
        'sku' => $faker->regexify('[A-Z]{3,5}[0-9]{5,5}'),
        'name' => $faker->randomElement([$faker->vegetable, $faker->dairy, $faker->meat, $faker->sauce, $faker->fruit]),
        'metric_id' => Metric::inRandomOrder()->first()->id,
        'inventory_category_id' => InventoryCategory::inRandomOrder()->first()->id,
    ];
});
