<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    //helper functions
    public function canEdit()
    {
        $bool = false;

        $start = (int)(now()->startOfMonth()->format('Ymd'));

        $end = (int)(now()->format('Ymd'));

        $date = (int)(Carbon::parse($this->date)->format('Ymd'));

        if($date >= $start && $date <= $end) {
            $bool = true;
        }

        return $bool;
    }

    //scope functions

    public function scopeFilterOn($query)
    {
        if(request('type')) {
            $query->whereHas('type', function($q) {
                $q->where('id', request('type'))->orWhere('slug', request('type'));
            });
        }

        if(request('q')) {
            $query->where('name', 'like', '%'. request('q') .'%');
        }

        if(request('date')) {
            $query->whereDate('date', request('date'));
        }
    }

    public function scopeFromTo($query)
    {
        $start_date = request()->from_date ? Carbon::parse(request()->from_date) : now()->startOfMonth();

        $end_date = request()->to_date ? Carbon::parse(request()->to_date) : now()->endOfMonth();

        $query->whereDate('date', '>=', $start_date)->whereDate('date', '<=', $end_date);
    }

}
