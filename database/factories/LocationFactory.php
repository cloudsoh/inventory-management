<?php
use Faker\Generator as Faker;

$factory->define(\CloudSoh\InventoryManagement\Location::class, function (Faker $faker) {
    $locations = ['Bar Table', 'Behind', 'Garage', 'Kitchen', 'Store room', 'Home', 'Shop'];

    return [
        'name' => $locations[array_rand($locations)],
    ];
});
