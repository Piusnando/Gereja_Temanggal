<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YouthAttendance extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(YouthMember::class, 'youth_member_id');
    }

    // Ubah ke Activity
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}