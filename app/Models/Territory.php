<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    protected $guarded = [];

    /**
     * PENTING: Fungsi ini HARUS bernama 'lingkungans'
     * karena di Controller Anda memanggil with('lingkungans')
     */
    public function lingkungans()
    {
        // Pastikan Model Lingkungan ada di App\Models\Lingkungan
        return $this->hasMany(Lingkungan::class);
    }
}