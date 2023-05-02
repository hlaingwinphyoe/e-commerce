<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $guarded = ['customer', 'amount'];

    protected $appends = ['discount', 'customer', 'deli_fee', 'org_amount'];

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'order_sku', 'order_id', 'sku_id')->withPivot(['qty', 'price', 'customized_price', 'buy_price', 'margin', 'status_id']);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function paymentype()
    {
        return $this->belongsTo(Status::class, 'paymentype_id');
    }

    public function transactions()
    {
        return $this->morphToMany(Transaction::class, 'transactionable');
    }

    public function transactions_in()
    {
        return $this->transactions()->whereHas('status', function ($q) {
            $q->where('slug', 'in');
        })->get();
    }

    public function transactions_out()
    {
        return $this->transactions()->whereHas('status', function ($q) {
            $q->where('slug', 'out');
        })->get();
    }

    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_order', 'order_id', 'delivery_id')->withPivot(['date', 'remark']);
    }

    public function parcels()
    {
        return $this->belongsToMany(Parcel::class, 'parcel_order', 'order_id', 'parcel_id');
    }

    public function returns()
    {
        return $this->hasMany(ReturnModel::class, 'order_id', 'id');
    }

    public function return()
    {
        return $this->returns->count() ? $this->returns()->first() : '';
    }

    /**
     * get attributes
     */
    public function getCustomerAttribute()
    {
        return $this->data ? json_decode($this->data)->user : '';
    }

    public function getAmountAttribute()
    {
        return $this->customized_price ?? $this->price;
    }

    public function getTotalQty()
    {
        $qty = $this->skus->sum(function ($sku){
            return $sku->pivot->qty;
        });
        return $qty;
    }

    /**
     * helper functions
     */

    public function updatePrice()
    {
        $cancel = Status::where('slug', 'sku-cancelled')->first();

        $total = $this->skus->sum(function ($sku) use ($cancel) {
            return $sku->pivot->status_id != $cancel->id ? $sku->pivot->price * $sku->pivot->qty : 0;
        });

        $this->price = $total;
        // $this->customized_price = $total;

        $this->update();
        if ($this->getBalance() > 0) {
            $this->update(['debt' => $this->getBalance()]);
        }
    }

    public function updateStockPrice()
    {
        $cancel = Status::where('slug', 'sku-cancelled')->first();

        $total = $this->skus->sum(function ($sku) use ($cancel) {
            return $sku->status_id != $cancel->id ? $sku->price * $sku->qty : 0;
        });

        $this->price = $total;
        $this->update();

        if ($this->getBalance() > 0) {
            $this->update(['debt' => $this->getBalance()]);
        }
    }

    public function updateStatus($name)
    {
        $status = Status::where('slug', $name)->first();

        if ($status) {
            $this->update(['status_id' => $status->id]);
        }
    }

    public function updateSkuStatus($status = 'pending')
    {
        $status = Status::where('slug', $status)->first();

        $accepted = Status::where('slug', 'order-accepted')->first();

        foreach ($this->skus as $sku) {
            if ($sku->status_id != $status->id) {
                if ($status->slug == 'sku-cancelled' && $sku->pivot->status_id === $accepted->id) {
                    $sku->update(['stock' => $sku->stock + $sku->pivot->qty]);
                }
                $sku->pivot->update(['status_id' => $status->id]);
            }
        }
    }

    public function updateStockStatus($status = 'pending')
    {
        $status = Status::where('slug', $status)->first();

        $accepted = Status::where('slug', 'order-accepted')->first();

        foreach ($this->skus as $stock) {
            if ($stock->status_id != $status->id) {
                if ($stock->sku && $status->slug == 'sku-cancelled' && $stock->status_id === $accepted->id) {
                    $stock->sku->update(['stock' => $stock->sku->stock + $stock->qty]);
                }
                $stock->update(['status_id' => $status->id]);
            }
        }
    }

    public function updateSkuStock($type = null)
    {
        if ($type && $type == 'add') {
            foreach ($this->skus as $sku) {
                $sku->update(['stock' => $sku->stock + $sku->pivot->qty]);
            }
        } else {
            foreach ($this->skus as $sku) {
                $sku->update(['stock' => $sku->stock - $sku->pivot->qty]);
            }
        }
    }

    public function updateOrderedStock($type = null)
    {
        if ($type && $type == 'add') {
            foreach ($this->skus as $stock) {
                if ($stock->sku) {
                    $stock->sku->update(['stock' => $stock->sku->stock + $stock->qty]);
                }
            }
        } else {
            foreach ($this->skus as $stock) {
                if ($stock->sku) {
                    $stock->sku->update(['stock' => $stock->sku->stock - $stock->qty]);
                }
            }
        }
    }

    public function hasPreOrderedSku()
    {
        $bool = false;

        $status = Status::where('slug', 'pre-ordered')->first();
        foreach ($this->skus as $sku) {
            if ($sku->pivot->status_id == $status->id) {
                $bool = true;
            }
        }
        return $bool;
    }

    public function hasPreOrderedStock()
    {
        $bool = false;

        $status = Status::where('slug', 'pre-ordered')->first();
        foreach ($this->skus as $stock) {
            if ($stock->status_id == $status->id) {
                $bool = true;
            }
        }
        return $bool;
    }

    public function getPreOrderedSkus()
    {
        $status = Status::where('slug', 'pre-ordered')->first();

        return $this->skus()->wherePivot('status_id', $status->id)->get();
    }

    public function getPreorderedStocks()
    {
        $status = Status::where('slug', 'pre-ordered')->first();

        return $this->skus()->where('status_id', $status->id)->get();
    }

    public function getPointsAttribute()
    {
        $points = 0;

        if ($this->buyer && $this->status->slug !== 'cancel') {
            $price = $this->customized_price ?? $this->price;

            $role = Role::where('slug', 'guest')->first();

            $bonuspoint = $this->buyer->role->bonuspoints() && $this->buyer->role->bonuspoints()->count() ? $this->buyer->role->bonuspoints()->latest()->first() : $role->bonuspoints()->latest()->first();

            $points = $bonuspoint ? floor($bonuspoint->points * ($price / $bonuspoint->amt)) : 0;
        }
        return $points;
    }

    public function getSubTotal()
    {
        return $this->price - $this->discount + $this->deli_fee;
    }

    public function getPayAmount()
    {
        return $this->transactions->reduce(function ($total, $transaction) {
            return $transaction->status->slug == 'in' ? $total + $transaction->amount : $total;
        }, 0);
    }

    public function getChange()
    {
        return $this->transactions->reduce(function ($total, $transaction) {
            return $transaction->status->slug == 'out' ? $total + $transaction->amount : $total;
        }, 0);
    }

    public function getBalance()
    {
        return $this->getSubTotal() - $this->getPayAmount();
    }

    public function getChangeBalance()
    {
        return $this->getPayAmount() - $this->getSubTotal();
    }

    public function getReturnAmount()
    {
        if ($this->return()) {
            return $this->return()->transactions->reduce(function ($total, $transaction) {
                return $total + $transaction->amount;
            }, 0);
        } else {
            return 0;
        }
    }

    public function getCost()
    {
        $status = Status::where('slug', 'order-accepted')->first();
        return $this->skus()->where('status_id', $status->id)->get()->reduce(function ($total, $sku) {
            return $total + ($sku->qty * $sku->buy_price);
        }, 0);
    }

    public function getProfit($type = 'amt')
    {
        if ($type == 'amt') {
            return $this->price - $this->discount - $this->getCost();
        } else {
            return (($this->price - $this->discount - $this->getCost()) * 100) / ($this->price - $this->discount);
        }
    }

    public function getOrderNumberAttribute()
    {
        $order_no = '';

        switch ($this->type) {
            case 'order':
                $order_no .= 'O_';
                break;
            case 'pos':
                $order_no .= 'P_';
                break;
            case 'pre-order':
                $order_no .= 'Pre_';
                break;
        }

        $order_no .= $this->order_no;

        return $order_no;
    }

    public function getTownship()
    {
        $data = json_decode($this->data);

        $township = Township::find($data->user->region->township_id);

        return $township ? $township->name : ' - ';
    }

    public function getRegion()
    {
        $data = json_decode($this->data);

        $region = Region::find($data->user->region->region_id);

        return $region ? $region->name : ' - ';
    }

    //scope functions

    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeSaleOrder($query)
    {
        $query->whereHas('status', function ($q) {
            $q->where('slug', 'completed');
        });
    }

    public function scopeWaitOrder($query)
    {
        $query->whereHas('status', function ($q) {
            $q->where('slug', 'order-confirmed');
        });
    }

    public function scopeNotSale($query)
    {
        $query->whereHas('status', function ($q) {
            $q->whereNotIn('slug', ['completed']);
        });
    }

    public function scopeSaleMonth($query, $month)
    {
        $start_date = Carbon::parse($month);
        $end_date  = Carbon::parse($month)->endOfMonth();
        $query->whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date);
    }

    public function scopeCustomerOrder($query)
    {
        $query->where('customer_id', auth()->user()->id);
    }

    public function scopeFromTo($query)
    {
        if (!(request()->from_date || request()->to_date)) {
            $start_date = now()->startOfMonth();

            $end_date = now()->endOfMonth();

            $query->whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date);
        }
    }

    public function scopeFilterOn($query)
    {
        if (request('order_no')) {
            $query->where('order_no', 'like', '%' . request('order_no'));
        }

        if(request('q')) {
            $query->where('order_no', 'like', '%' . request('q'). '%');
        }

        if (request('from_date')) {
            $from_date = Carbon::parse(request('from_date'));
            $to_date = request('to_date') ? Carbon::parse(request('to_date')) : now();
            $query->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
        }

        if (request('status')) {
            $query->where('status_id', request('status'));
        } else {
            $query->whereHas('status', function ($q) {
                $q->where('slug', '!=', 'cancel');
            });
        }

        if (request('delivery')) {
            $query->whereHas('deliveries', function ($query) {
                $query->where('id', request('delivery'));
            });
        }

        if (request('sku')) {
            $query->whereHas('skus', function ($query) {
                // $query->whereHas('sku', function ($query) {
                    $query->where('id', request('sku'));
                // });
            });
        }

        if (request('customer')) {
            $name = ucwords(request()->customer);
            $query->where('data->user->name', 'like', '%' . $name . '%')
                ->orWhere('data->user->name', 'like', '%' . request('customer') . '%')
                ->orWhere('data->user->phone', 'like', '%' . request('customer') . '%');
        }

        if (request('type')) {
            $query->where('type', request('type'));
        }

        if (request('role_id')) {
            $query->whereHas('buyer', function ($q) {
                $q->whereHas('role', function ($q) {
                    $q->where('id', request('role_id'));
                });
            });
        }
    }

    public function scopeTodayFilter($query)
    {
        $query->whereDate('created_at', now());
    }

    public function scopePendingOrder($query)
    {
        $query->whereHas('status', function ($q) {
            $q->where('slug', 'pending');
        });
    }

    public function getDiscountAttribute()
    {
        $status = Status::where('slug', 'percent')->first();
        if ($this->discount_amt != null) {
            $discount_amt = $this->discount_status == $status->id ? ($this->price * $this->discount_amt) / 100 : $this->discount_amt;
            // return round($discount_amt / 100) * 100;
            return $discount_amt;
        }
        return 0;
    }

    public function getDeliFeeAttribute()
    {
        return $this->data ? intval(json_decode($this->data)->user->delifee) : 0;
    }

    public function getOrgAmountAttribute()
    {
        return $this->skus->reduce(function ($total, $sku) {
            return $total + $sku->buy_price;
        });
    }
}
