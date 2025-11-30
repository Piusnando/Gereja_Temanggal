<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiturgyAssignment extends Model
{
    protected $guarded = [];

    public function personnel() {
        return $this->belongsTo(LiturgyPersonnel::class, 'liturgy_personnel_id');
    }

    public function schedule() {
        return $this->belongsTo(LiturgySchedule::class, 'liturgy_schedule_id');
    }

    public function lingkungan() {
        return $this->belongsTo(Lingkungan::class, 'lingkungan_id');
    }
}
