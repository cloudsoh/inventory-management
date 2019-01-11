<?php

use Faker\Generator as Faker;
use CloudSoh\InventoryManagement\Metric;
use CloudSoh\InventoryManagement\Measurement;

$factory->define(Measurement::class, function (Faker $faker) {
    $names = [
        'cup',
        'bowl',
        'tupperware',
        'plate',
        'bag',
        'barrel',
        'plastic',
        'can',
        'tray',
        'box',
        'pack',
    ];

    return [
        'name' => $faker->randomElement($names),
        'metric_id' => Metric::inRandomOrder()->first()->id,
        'quantity' => mt_rand(1, 5000),
    ];
});
