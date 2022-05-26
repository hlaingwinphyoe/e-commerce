<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Maintype extends Model
{
    use HasFactory;

    protected $table = 'maintypes';

    protected $guarded = [];

    protected $appends = ['thumbnail'];

    public function types()
    {
        return $this->belongsToMany(Type::class, 'main_type', 'maintype_id', 'type_id');
    }

    public function available_types()
    {
        return $this->types()->where('disabled', 0)->orderBy('name');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getItems($slug)
    {
            $types = $this->where('slug',$slug)->first()->types->pluck('id');
            $items = Item::notDisabled()->whereHas('types', function ($query) use ($types){
                 $query->whereIn('id', $types);
            })->get()->take(5);
    
            return $items;
    }

    public function getCateItems($slug)
    {
        $types = $this->where('slug',$slug)->first()->types()->notDisabled()->pluck('id');
        $items = Item::notDisabled()->whereHas('types', function ($query) use ($types){
             $query->whereIn('id', $types);
        });

        return $items;
    }

    //scope functions
    public function scopeFilterOn($query)
    {
        //
    }

    public function getThumbnailAttribute()
    {
        $img = $this->medias()->where('is_check', true)->first();

        $other_img = $this->medias()->first();
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
