<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $guarded = [];

    public $timestamps = false;

    public function townships()
    {
        return $this->hasMany(Township::class, 'region_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function enabled_townships()
    {
        return $this->townships()->where('disabled', 0);
    }

    //scope functions
    public function scopeFilterOn($query)
    {
        if(request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%')
                ->orWhere('mm_name', 'like', '%' . request('q'), '%');
        }

        if(request('country_id')) {
            $query->where('country_id', request('country_id'));
        }

        if (request('disabled') && request('disabled') == 'disabled') {
            $query->where('disabled', 1);
        } else {
            $query->where('disabled', 0);
        }
    }

    public function scopeIsEnabled($query)
    {
        $query->where('disabled', 0);
    }
}
