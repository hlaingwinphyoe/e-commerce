<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $table = 'costs';

    protected $guarded = [];

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'sku_cost', 'cost_id', 'sku_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    //helper functions


    //scope functions
    public function scopeFilterOn($query)
    {
        //
    }
}
