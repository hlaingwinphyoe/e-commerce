<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class, 'unit_id', 'id');
    }

    public function weights()
    {
        return $this->hasMany(Weight::class, 'unit_id' , 'id');
    }

    public function parent_unit()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function child_units()
    {
        return $this->hasMany(Unit::class, 'parent_id', 'id');
    }
}
