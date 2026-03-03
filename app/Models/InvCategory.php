<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvCategory extends Model {
    protected $guarded =[];
    public function items() { return $this->hasMany(InvItem::class); }
}