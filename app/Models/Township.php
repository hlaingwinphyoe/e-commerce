<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    use HasFactory;

    protected $table = 'townships';

    protected $guarded = [];

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'township_id', 'id');
    }

    public function delifees()
    {
        return $this->belongsToMany(DeliFee::class, 'deli_township', 'township_id', 'deli_fee_id');
    }

    public function delifee()
    {
        return $this->delifees()->first();
    }

    //helper functions
    public function canShow()
    {
        return $this->disabled == 0 && ($this->delifees()->count() == 0 );
    }

    //scope functions
    public function scopeFilterOn($query)
    {
        if(request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%')
                ->orWhere('mm_name', 'like', '%' . request('name'), '%');
        }

        if(request('region_id')) {
            $query->where('region_id', request('region_id'));
        }

        if (request('disabled') && request('disabled') == 'disabled') {
            $query->where('disabled', 1);
        } else {
            $query->where('disabled', 0);
        }
    }
}
