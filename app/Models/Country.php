<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected $guarded = [];

    public $timestamps = false;

    public function regions()
    {
        return $this->hasMany(Region::class, 'country_id', 'id');
    }

    // public function enabled_regions()
    // {
    //     return $this->regions()->where('disabled', 0);
    // }

    public function scopeNotDisabled($query)
    {
        $query->whereHas('regions', function ($q) {
            $q->where('disabled', 0);
        });
    }

    public function scopeFilterOn($query)
    {
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%')
                ->orWhere('mm_name', 'like', '%' . request('name'), '%');
        }


        if (request('disabled') && request('disabled') == 'disabled') {
            $query->where('disabled', 1);
        } else {
            $query->where('disabled', 0);
        }
    }
}
