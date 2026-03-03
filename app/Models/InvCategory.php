<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvItem; // <-- TAMBAHKAN INI

class InvCategory extends Model {
    protected $guarded =[];
    public function items() { return $this->hasMany(InvItem::class); }
}