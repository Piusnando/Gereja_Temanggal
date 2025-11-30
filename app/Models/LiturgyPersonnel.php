<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiturgyPersonnel extends Model
{
    protected $guarded = [];

    // Relasi ke Lingkungan (pastikan model Lingkungan sudah ada)
    public function lingkungan() {
        return $this->belongsTo(Lingkungan::class, 'lingkungan_id'); 
        // Sesuaikan nama class jika masih Community
    }

    public function assignments() {
        return $this->hasMany(LiturgyAssignment::class);
    }
}
