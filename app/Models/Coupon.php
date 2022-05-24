<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $guarded = [];

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function maintype()
    {
        return $this->belongsTo(Maintype::class, 'type_id');
    }

    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    public function getAmount($total)
    {
        if ($this->type == 'fixed') {
            return $this->value;
        } else if ($this->type == 'percent') {
            return ($this->percent_off * $total) / 100;
        } else {
            return 0;
        }
    }

    // scope functions
    public function scopeFilterOn($query)
    {
        if (request('type_id')) {
            $query->where('type_id', request('type_id'));
        }

        if (request('type')) {
            $query->where('type', request('type'));
        }

        if (request('q')) {
            $query->where('code', 'like', '%' . request('q') . '%');
        }

        if (request('is_used')) {
            $is_used = request('is_used') == 'active' ? 0 : 1;
            $query->where('is_used', $is_used);
        }
    }
}
