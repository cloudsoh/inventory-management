<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        $this->publishes([
            __DIR__."/../config/inventory.php" => config_path('inventory.php')
        ], 'inventory-config');
        $this->publishes([
            __DIR__."/../database/migrations/" => database_path('migrations')
        ], 'inventory-migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__."/../config/inventory.php",
            'inventory'
        );
    }
}
