<?php
namespace App\Models;
use App\Models\YouthAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class YouthMember extends Model
{
    protected $guarded = [];
    protected $casts =['birth_date' => 'date'];

    // Relasi
    public function territory() { return $this->belongsTo(Territory::class); }
    public function lingkungan() { return $this->belongsTo(Lingkungan::class); }
    public function attendances() { return $this->hasMany(YouthAttendance::class); }

    // Hitung Umur Otomatis
    public function getAgeAttribute()
    {
        if (!$this->birth_date) return '-';
        return Carbon::parse($this->birth_date)->age . ' Tahun';
    }

    // Hitung Persentase Keaktifan (Frekuensi Hadir)
    public function getAttendancePercentageAttribute()
    {
        $totalEvents = YouthEvent::where('category', $this->category)->count();
        if ($totalEvents == 0) return 0;
        
        $attended = $this->attendances()->where('status', 'Hadir')->count();
        return round(($attended / $totalEvents) * 100);
    }
}