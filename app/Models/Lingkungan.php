<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lingkungan extends Model
{
    protected $guarded = [];

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }
}