<?php

use CloudSoh\InventoryManagement\Metric;
use CloudSoh\InventoryManagement\Measurement;
use CloudSoh\InventoryManagement\Inventory;
use CloudSoh\InventoryManagement\InventoryCategory;
use CloudSoh\InventoryManagement\InventoryMovement;

return [
    'class' => [
        'inventory' => Inventory::class,
        'category' => InventoryCategory::class,
        'movement' => InventoryMovement::class,
        'metric' => Metric::class,
        'measurement' => Measurement::class,
    ],
];