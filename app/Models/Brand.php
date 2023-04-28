<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $guarded = [];

    // public function items()
    // {
    //     return $this->belongsToMany(Item::class, 'item_brand', 'brand_id', 'item_id');
    // }

    public function items()
    {
        return $this->hasMany(Item::class, 'brand_id', 'id');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //scope functions
    public function scopeFilterOn($query)
    {
        if(request('q')) {
            $query->where('name', 'like', '%'. request('q') .'%');
        }
    }
}
