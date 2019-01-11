<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Metric.
 */
class Metric extends Model
{
    protected $fillable = [
        'name',
        'symbol',
    ];

    protected $casts = [
        'modifiable' => 'boolean'
    ];

    public function inventories()
    {
        return $this->hasMany(config('inventory.class.inventory'));
    }

    public function parent()
    {
        return $this->hasMany(config('inventory.class.metric'));
    }

    public function measurements()
    {
        return $this->hasMany(config('inventory.class.measurement'));
    }
}
