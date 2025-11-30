<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiturgySchedule extends Model
{
    protected $guarded = [];
    protected $casts = ['event_at' => 'datetime'];

    public function assignments() {
        return $this->hasMany(LiturgyAssignment::class);
    }
}
