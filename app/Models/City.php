<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $guarded = [];

    public $timestamps = false;

    public function township()
    {
        return $this->belongsTo(Township::class, 'township_id');
    }

    //scope functions
    public function scopeFilterOn($query)
    {
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%')
                ->orWhere('mm_name', 'like', '%' . request('name'), '%');
        }

        if (request('township_id')) {
            $query->where('township_id', request('township_id'));
        }

        if (request('region_id')) {
            $query->whereHas('township', function ($query) {
                $query->where('region_id', request('region_id'));
            });
        }

        if(request('district_id')) {
            $query->whereHas('township', function($query) {
                $query->where('district_id', request('district_id'));
            });            
        }
    }
}
