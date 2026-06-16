<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageBox extends Model
{
    protected $guarded = [];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function placements()
    {
        return $this->hasMany(StockPlacement::class);
    }
}
