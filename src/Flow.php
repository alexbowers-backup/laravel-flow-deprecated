<?php

namespace Laravel\Flow;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'timestamp',
        'available_at' => 'timestamp',
    ];

    protected $dates = [
        'completed_at',
        'available_at',
    ];
}
