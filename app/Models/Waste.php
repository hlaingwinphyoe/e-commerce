<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    use HasFactory;

    protected $table = 'wastes';

    protected $guarded = [];

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'sku_waste', 'waste_id', 'sku_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //helper functions


    //scope functions
    public function scopeFilterOn($query)
    {
        //
    }
}
