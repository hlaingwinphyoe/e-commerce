<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $guarded = [];

    protected $appends = ['thumbnail'];

    public function parent_type()
    {
        return $this->belongsTo(Type::class, 'parent_id', 'id');
    }

    public function sub_types()
    {
        return $this->hasMany(Type::class, 'parent_id');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_type', 'type_id', 'item_id');
    }

    public function featured_images()
    {
        return $this->medias()->where('type', 'category')->get();
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

    //helper functions
    // public function hasMaintype($maintype)
    // {
    //     $bool = false;
    //     foreach($this->maintypes as $main) {
    //         if($main->id == $maintype) {
    //             $bool = true;
    //         }
    //     }
    //     return $bool;
    // }

    public function hasParent($id)
    {
        return $this->parent_type && $this->parent_type->id == $id;
    }

    public function getChild()
    {
       $types = Type::where('parent_id',$this->id)->get();
       return $types;
    }


    //scope functions

    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeFilterOn($query)
    {

        if (request('disabled') && request('disabled') == 'disabled') {
            $query->where('disabled', 1);
        } else {
            $query->where('disabled', 0);
        }

        if (request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }

        if(request('parent')) {
            if(request('parent') == 'parent') {
                $query->where('parent_id', 0);
            }else {
                $query->where('parent_id', '!=', 0);
            }
        }

        if(request('parent_type')) {
            $query->where('parent_id', request('parent_type'));
        }

        if (request('maintype')) {
            $query->whereHas('maintypes', function($query) {
                $query->where('id', request('maintype'));
            });
        }

    }

    public function scopeNotMaintype($query, $maintype)
    {
        $query->whereHas('maintypes', function ($query) use ($maintype) {
            $query->where('slug', '!=', $maintype);
        });
    }

    public function scopeIsMaintype($query, $maintype)
    {
        $query->whereHas('maintypes', function ($query) use ($maintype) {
            $query->where('slug', $maintype);
        });
    }

    public function scopeNotDisabled($query)
    {
        $query->where('disabled', 0);
    }


    public function getThumbnailAttribute()
    {
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
            $path = asset('/images/default.png');
        }
        return $path;
    }


    public function getExpenseTotal($start, $end)
    {
        $expenses = $this->expenses()->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get();

        return $expenses->reduce(function($total, $expense) {
            return $total + $expense->amount;
        }, 0);
    }

}
