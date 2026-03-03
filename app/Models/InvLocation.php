<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvLocation extends Model {
    protected $guarded =[];
    public function items() { return $this->hasMany(InvItem::class); }
}