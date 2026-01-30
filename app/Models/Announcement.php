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
        'is_pinned'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}