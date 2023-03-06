<?php

namespace App\Models;

use App\Traits\ItemTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory, SoftDeletes, ItemTrait;

    protected $table = 'items';

    protected $guarded = [];

    protected $appends = ['price', 'min_price', 'discount', 'thumbnail', 'images'];

    public function skus()
    {
        return $this->hasMany(Sku::class, 'item_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function pricings()
    {
        return $this->belongsToMany(Pricing::class, 'item_pricing', 'item_id', 'pricing_id');
    }

    public function costs()
    {
        return $this->belongsToMany(Cost::class, 'item_cost', 'item_id', 'cost_id');
    }

    public function wastes()
    {
        return $this->belongsToMany(Waste::class, 'item_waste', 'item_id', 'waste_id');
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'item_id', 'id');
    }

    public function main_attribute()
    {
        return $this->attributes()->where('parent_id', 0)->first();
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'item_id', 'id');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'item_discount', 'item_id', 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'item_type', 'item_id', 'type_id');
    }

    public function type()
    {
        return $this->types()->latest()->first();
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'item_brand', 'item_id', 'brand_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function featured_images()
    {
        return $this->medias()->where('type', 'item')->get();
    }

    public function gallery_images()
    {
        return $this->medias()->where('type', 'gallery')->get();
    }

    public function orders()
    {
        return $this->morphToMany(Order::class, 'orderable')->withPivot(['qty', 'price', 'customized_price', 'margin']);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    //get attributes functions
    public function getThumbnailAttribute()
    {
        // $img = $this->medias()->first();
        $img = $this->featured_images()->where('is_check', true)->first();

        $other_img = $this->featured_images()->first();
        $path = '';
        if ($img) {
            if (Storage::exists('public/thumbnail/' . $img->slug)) {
                $path = Storage::url('public/thumbnail/' . $img->slug);
            } else {
                $path = $img->url;
            }
        } elseif ($other_img) {
            if (Storage::exists('public/thumbnail/' . $other_img->slug)) {
                $path = Storage::url('public/thumbnail/' . $other_img->slug);
            } else {
                $path = $other_img->url;
            }
        } else {
            $path = asset('/images/featured/default.png');
        }
        return $path;
    }
}
