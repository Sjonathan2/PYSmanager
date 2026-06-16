<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPlacement extends Model
{
    protected $guarded = [];

    public function storageBox()
    {
        return $this->belongsTo(StorageBox::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
