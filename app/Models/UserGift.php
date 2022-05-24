<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGift extends Model
{
    use HasFactory;

    protected $table = 'usergifts';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_gift', 'usergift_id', 'delivery_id')->withPivot(['date', 'remark']);
    }

    public function delivery()
    {
        return $this->deliveries ? $this->deliveries()->first() : '';
    }

    public function scopeFilterOn($query)
    {
        if(request('item_name')) {
            $query->whereHas('gift', function ($q) {
                $q->where('name', 'like', '%' . request('item_name') . '%');
            });
        }

        if(request('q')) {
            $query->whereHas('gift', function ($q) {
                $q->where('id', request('q'))->orWhere('name', 'like', request('q') . '%');
            });
        }

        if(request('user_name')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . request('user_name') . '%');
            });
        }

        if(request('status_id')) {
            $query->whereHas('status', function ($q) {
                $q->where('id', request('status_id'));
            });
        }
    }
}
