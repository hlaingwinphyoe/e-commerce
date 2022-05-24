<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $guarded = [];

    public function discountype()
    {
        return $this->belongsTo(DiscountType::class, 'discountype_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'sku_discount', 'discount_id', 'sku_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_discount', 'discount_id', 'item_id');
    }
}
