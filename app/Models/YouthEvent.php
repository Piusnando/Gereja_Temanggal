<?php
namespace App\Models;
use App\Models\YouthAttendance;
use Illuminate\Database\Eloquent\Model;

class YouthEvent extends Model
{
    protected $guarded =[];
    protected $casts = ['event_date' => 'datetime'];

    public function attendances() {
        return $this->hasMany(YouthAttendance::class);
    }
}