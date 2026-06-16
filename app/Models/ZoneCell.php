<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoneCell extends Model
{
    protected $guarded = [];

    protected $casts = [
        'x' => 'integer',
        'y' => 'integer',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
