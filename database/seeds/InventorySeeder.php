<?php

namespace CloudSoh\InventoryManagement\Database\Seeds;

use Illuminate\Database\Seeder;
use CloudSoh\InventoryManagement\Inventory;
use CloudSoh\InventoryManagement\Product;
use CloudSoh\InventoryManagement\Measurement;
use CloudSoh\InventoryManagement\InventoryCategory;
use Illuminate\Support\Facades\Auth;
use CloudSoh\InventoryManagement\User;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Measurement::class, 10)->create();
        factory(InventoryCategory::class, 10)->create();
        $inventories = factory(Inventory::class, 20)->create();

        foreach ($inventories as $inventory) {
            Auth::loginUsingId(User::first()->id);
            \DB::table('inventory_movements')->insert([
                'id' => (string) Str::uuid(),
                'inventory_id' => $inventory->id,
                'causable_id' => Auth::id(),
                'causable_type' => User::class,
                'quantity' => mt_rand(5000, 300000),
                'remarks' => 'Open Stock',
                'created_at' => $date = now()->subSecond(mt_rand(1, 3 * 365 * 24 * 60)), // any second between 3 years
                'updated_at' => $date,
            ]);

            for ($i = 0; $i < mt_rand(3, 10); $i++) {
                \DB::table('inventory_product')->insert([
                    'id' => (string) Str::uuid(),
                    'inventory_id' => $inventory->id,
                    'product_id' => Product::inRandomOrder()->first()->id,
                    'quantity' => mt_rand(1, 50),
                    'measurement_id' => mt_rand(0, 100) > 50 ? Measurement::inRandomOrder()->first()->id : null,
                    'created_at' => $date = now()->subSecond(mt_rand(3 * 365 * 24 * 60, 4 * 365 * 24 * 60)), // any second between -3 & -4 years
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
