<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $guarded = [];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    
    public function values()
    {
        return $this->hasMany(Value::class, 'attribute_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'attribute_id', 'id');
    }

    public function sub_attributes()
    {
        return $this->hasMany(Attribute::class, 'parent_id', 'id');
    }
}
