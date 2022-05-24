<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $guarded = [];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'supplier_id', 'id');
    }

    public function scopeFilterOn($query)
    {
        //
    }
}
