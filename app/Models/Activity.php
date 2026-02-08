<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function lingkungan() {
        return $this->belongsTo(Lingkungan::class);
    }
}