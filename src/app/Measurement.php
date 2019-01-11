<?php

namespace CloudSoh\InventoryManagement;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    public static $precision = 3;

    protected $fillable = [
        'name',
        'quantity',
    ];

    public function metric()
    {
        return $this->belongsTo(config('inventory.class.metric'));
    }
}
