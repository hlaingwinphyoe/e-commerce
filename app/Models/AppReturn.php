<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $guarded = [];

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'return_sku', 'return_id', 'sku_id')->withPivot(['qty', 'price', 'remark']);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function transactions()
    {
        return $this->morphToMany(Transaction::class, 'transactionable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //mutators
    public function getPriceAttribute()
    {
        $price = 0;

        foreach($this->skus as $sku) {
            $price += $sku->pivot->price * $sku->pivot->qty;
        }

        return $price;
    }

    //helper functions
    public function getTotalQty() {
        return $this->skus->reduce(function($total, $sku){
            return $total + $sku->pivot->qty;
        },0);
    }

    public function scopeFilterOn($query)
    {
        if (request('q')) {
            $query->where('order_id', 'like', '%' . request('q') . '%');
        }

        if (request('township_id')) {
            $query->whereHas('townships', function($query) {
                $query->where('township_id', request('township_id'));
            });
        }
    }

}
