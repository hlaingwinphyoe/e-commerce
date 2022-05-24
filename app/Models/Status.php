<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'paymentype_id', 'id');
    }

    //scope functions
    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeFilterOn($query)
    {
        if (request('type')) {
            $query->where('type', request('type'));
        }
    }

    //helper function
    public function getTotalTransactionsByMonth($type = 'in')
    {
        $transactions = $this->transactions()->whereHas('status', function($q) use($type){
            $q->where('slug', $type);
        })->get();

        $total = $transactions->reduce(function($total, $transaction){
            return $total + $transaction->amount;
        }, 0);

        return [
            'qty' => $transactions->count(),
            'total' => $total
        ];
    }
}
