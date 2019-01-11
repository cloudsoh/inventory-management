<?php

Route::group(['namespace' => 'CloudSoh\InventoryManagement\Controllers'], function () {
    Route::prefix('api')->group(function () {
        Route::post('metrics/{metric}/add-measurement', 'MeasurementController@store');

        Route::get('inventory-categories/{inventoryCategory}/inventories', 'InventoryCategoryController@getInventories');

        Route::apiResource('measurements', 'MeasurementController')->except([
            'store',
        ]);

        Route::apiResources([
            'metrics' => 'MetricController',
            'inventories' => 'InventoryController',
            'inventory-categories' => 'InventoryCategoryController',
            'inventory-metrics' => 'InventoryMetricController',
        ]);
    });
});
