<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftInventory extends Model
{
    use HasFactory;

    protected $table = 'gift_inventories';

    protected $guarded = [];

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id');
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //scope functions

    public function scopeFilterOn($query)
    {
        //
    }

    public function scopeToday($query)
    {
        $query->whereDate('date', now())->where('is_published', 0);
    }
}
