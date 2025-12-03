<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lingkungan()
    {
        return $this->belongsTo(Lingkungan::class);
    }
}