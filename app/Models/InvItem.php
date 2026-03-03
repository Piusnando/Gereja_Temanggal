<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvItem extends Model {
    protected $guarded =[];

    public function location() { return $this->belongsTo(InvLocation::class, 'inv_location_id'); }
    public function category() { return $this->belongsTo(InvCategory::class, 'inv_category_id'); }
}