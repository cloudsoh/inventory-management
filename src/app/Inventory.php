<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'user_id',
        'inventory_category_id',
        'metric_id',
        'name',
        'sku',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(config('inventory.class.category'), 'inventory_category_id');
    }

    public function metric()
    {
        return $this->belongsTo(config('inventory.class.metric'));
    }

    public function movements()
    {
        return $this->hasMany(config('inventory.class.movement'));
    }

    public function getCurrentQuantity()
    {
        return $this->movements()->sum('quantity');
    }

    public function createMovement($quantity, $remarks)
    {
        return $this->movements()->create([
            'quantity' => $quantity,
            'remarks' => $remarks,
        ]);
    }
}
