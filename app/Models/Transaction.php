<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function paymentype()
    {
        return $this->belongsTo(Status::class, 'paymentype_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->morphedByMany(Order::class, 'transactionable');
    }

    public function inventories()
    {
        return $this->morphedByMany(Inventory::class, 'transactionable');
    }

    public function returns()
    {
        return $this->morphedByMany(ReturnModel::class, 'transactionable');
    }

    public function scopeFilterOn($query)
    {
        if (request('order_no')) {
            $query->whereHas('orders', function ($query) {
                $query->where('order_no', 'like', '%' . request('order_no'));
            });
        }

        if (request('from_date')) {
            $from_date = Carbon::parse(request('from_date'));
            $to_date = request('to_date') ? Carbon::parse(request('to_date')) : '';
            if ($to_date != '') {
                $query->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date);
            } else {
                $query->whereDate('date', $from_date);
            }
        }

        if (request('paymentype_id')) {
            $query->where('paymentype_id', request('paymentype_id'));
        }

        if (request('type')) {
            $query->whereHas('orders', function ($query) {
                $query->where('type', request('type'));
            });
        }
    }
}
