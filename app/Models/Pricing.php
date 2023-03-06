<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricings';

    protected $guarded = [];

    protected $appends = ['exchange_rate'];

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'sku_pricing', 'pricing_id', 'sku_id');
    }

    /* NO NEED FOR QTY PRICING
    *  ENABLED THIS RELATION FOR MUTLI LEVEL PRICIING
    */

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_pricing', 'pricing_id', 'item_id');
    }

     public function role()
     {
         return $this->belongsTo(Role::class, 'role_id');
     }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    //helper functions
    public function getExchangeRateAttribute()
    {
        $sku = $this->skus()->first();

        $exchange_rate = $sku && $sku->currency ? $sku->currency->exchangerates()->latest()->first() : '';

        return $sku && $exchange_rate ? $exchange_rate->mmk : 1;
    }
}
