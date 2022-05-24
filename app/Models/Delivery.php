<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    protected $guarded = [];

    public function township()
    {
        return $this->belongsTo(Township::class, 'township_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'delivery_order', 'delivery_id', 'order_id')->withPivot(['date', 'remark']);
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'delivery_id', 'id');
    }

    public function scopeFilterOn($query)
    {
        //
    }
}
