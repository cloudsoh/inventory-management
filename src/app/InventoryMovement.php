<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    public static $precision = 6;

    protected $fillable = [
        'inventory_id',
        'quantity',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'double',
    ];

    public function inventory()
    {
        return $this->belongsTo(config('inventory.class.inventory'));
    }

    public function causable()
    {
        return $this->morphTo();
    }
}
