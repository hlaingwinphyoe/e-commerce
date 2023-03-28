<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function transactions()
    {
        return $this->morphToMany(Transaction::class, 'transactionable');
    }

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'sku_inventories', 'inventory_id', 'sku_id')->withPivot(['qty', 'rate', 'currency_id', 'amount', 'remark']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //helper functions

    public function stockUpdate($type = 'add')
    {
        if ($type == 'add') {
            $this->skus->map(function ($sku) {
                $sku->update([
                    'stock' => $sku->stock + $sku->pivot->qty
                ]);
                return $sku;
            });
        } else {
            $this->skus->map(function ($sku) {
                $sku->update([
                    'stock' => $sku->stock - $sku->pivot->qty
                ]);
                return $sku;
            });
        }
    }

    public function skuAmountUpdate()
    {
        $this->skus->map(function ($sku) {
            if ($sku->pivot->amount && $sku->pivot->amount > 0) {
                $sku->update([
                    'buy_price' => $sku->pivot->amount
                ]);
                return $sku;
            }
        });
    }

    public function getAmount()
    {
        $amount = 0;

        foreach ($this->skus as $sku) {
            $buy_price = $sku->pivot->amount > 0 ? $sku->pivot->amount : $sku->buy_price;

            $amount += $sku->pivot->qty * $buy_price;
        }

        return $amount;
    }

    public function hasNoAmount()
    {
        $boo = false;

        foreach ($this->skus as $sku) {
            if ($sku->pivot->amount && $sku->pivot->amount > 0) {
                $boo = true;
            }
        }
        return $boo;
    }

    public function getPayAmount()
    {
        return $this->transactions->reduce(function ($total, $transaction) {
            return $total + $transaction->amount;
        }, 0);
    }

    public function getBalance()
    {
        return $this->getAmount() - $this->getPayAmount();
    }

    public function scopeFilterOn($query)
    {
        if (request('q')) {
            // $query->whereHas('supplier', function ($query) {
            //     $query->where('name', 'like', '%'. request('q'). '%');
            // });

            $query->where('inventory_no', 'like', '%'. request('q'). '%');
        }

        if (request('item')) {
            $item = request('item');
            $query->whereHas('sku', function ($q) use ($item) {
                $q->whereHas('item', function ($qq) use ($item) {
                    $qq->where('name', 'like', '%' . $item . '%');
                });
            });
        }

        if (request('supplier_id')) {
            $supplier = request('supplier_id');
            $query->where('supplier_id', $supplier);
        }

        if (request('buy_price')) {
            $query->whereHas('skus', function ($query) {
                $query->whereNull('amount')->orWhere('amount', '=', 0);
            });
        }

        if (request('date')) {
            $query->whereDate('date', request('date'));
        }

        if (request('is_published')) {
            if (request('is_published') == 'is_published') {
                $query->where('is_published', 1);
            } else {
                $query->where('is_published', 0);
            }
        }

        if (request('from_date')) {
            $from_date = Carbon::parse(request('from_date'));
            $to_date = request('to_date') ? Carbon::parse(request('to_date')) : now();
            $query->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
        }

        // if (request('is_published')) {
        //     $query->where('is_published', 1);
        // }else{
        //     if(!request('buy_price')) {
        //         $query->where('is_published', 0);
        //     }
        // }


    }

    public function scopeFromTo($query)
    {
        $start_date = request()->from_date ? Carbon::parse(request()->from_date) : now()->startOfMonth();

        $end_date = request()->to_date ? Carbon::parse(request()->to_date) : now()->endOfMonth();

        $query->whereDate('updated_at', '>=', $start_date)->whereDate('updated_at', '<=', $end_date);
    }

    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

}
