<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    use HasFactory;

    protected $table = 'discountypes';

    protected $guarded = [];

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'discountype_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    //helper functions

    public function updateExpiredDate()
    {
        $this->discounts->map(function ($discount) {
            $discount->update([
                'expired' => $this->end_date
            ]);
            return $discount;
        });
    }



    //scope functions

    public function scopeAvailableIn($query)
    {
        // $query->whereDate('end_date', '<=', now())->orWhere('end_date', null)->whereHas('discounts.sku');
    }

    public function scopeFilterOn($query)
    {
        //
    }
}
