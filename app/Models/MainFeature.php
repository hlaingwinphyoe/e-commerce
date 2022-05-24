<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainFeature extends Model
{
    use HasFactory;

    protected $table = 'mainfeatures';

    protected $guarded = [];

    protected $appends = ['thumbnail'];

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    //scope functions
    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeIsEnabled($query)
    {
        $query->where('disabled', 0);
    }

    public function getThumbnailAttribute()
    {
        $img = $this->medias()->latest()->first();

        $path = '';
        if ($img) {
            if (Storage::exists('public/thumbnail/' . $img->slug)) {
                $path = Storage::url('public/thumbnail/' . $img->slug);
            } else {
                $path = $img->url;
            }
        } else {
            $path = asset('/images/default-1.png');
        }
        return $path;
    }
}
