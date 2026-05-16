<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'content', 
        'image_path', 
        'category', 
        'event_date',
        'is_pinned',
        'territory_id', 
        'lingkungan_id'
    ];
public function territory() { return $this->belongsTo(Territory::class); }
public function lingkungan() { return $this->belongsTo(Lingkungan::class); }

    protected $casts = [
        'event_date' => 'date',
    ];
}