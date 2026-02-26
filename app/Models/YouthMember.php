<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YouthMember extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function lingkungan()
    {
        return $this->belongsTo(Lingkungan::class);
    }

    public function attendances()
    {
        return $this->hasMany(YouthAttendance::class);
    }
}