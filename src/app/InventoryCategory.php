<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    public function inventories()
    {
        return $this->hasMany(config('inventory.class.inventory'));
    }
}
