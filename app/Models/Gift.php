<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Gift extends Model
{
    use HasFactory;

    protected $table = 'gifts';

    protected $guarded = [];

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function userGifts()
    {
        return $this->hasMany(UserGift::class, 'gift_id', 'id');
    }

    public function inventories()
    {
        return $this->hasMany(GiftInventory::class, 'gift_id', 'id');
    }

    public function scopeFilterOn($query)
    {
        if (request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }
    }

    public function getThumbnailAttribute()
    {
        $img = $this->medias()->first();
        $path = '';
        if ($img) {
            if (Storage::exists('public/thumbnail/' . $img->slug)) {
                $path = Storage::url('public/thumbnail/' . $img->slug);
            } else {
                $path = $img->url;
            }
        } else {
            $path = asset('/images/featured/default.png');
        }
        return $path;
    }

    //helper functions
    public function getTotalInventoriesCount()
    {
        $count = 0;

        foreach ($this->inventories()->where('is_published', '1')->get() as $inventory) {
            $count += $inventory->qty;
        }
        return $count;
    }

    public function getTotalUsedCount()
    {
        return $this->userGifts->count();
    }
}
