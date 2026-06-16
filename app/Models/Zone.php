<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $guarded = [];

    public function cells()
    {
        return $this->hasMany(ZoneCell::class);
    }

    public function storageBoxes()
    {
        return $this->hasMany(StorageBox::class)->orderBy('order_index');
    }
}
