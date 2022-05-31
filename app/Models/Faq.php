<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $guarded = [];

    public function faq_type()
    {
        return $this->belongsTo(FaqType::class, 'faq_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //helper functions


    //scope functions
    public function scopeFilterOn($query)
    {
        if (request('q')) {
            $query->where('title', 'like', '%' . request('q') . '%');
        }

        if(request('faq_type')) {
            $query->where('faq_type_id', request('faq_type'));
        }
    }

    public function scopeIsType($query, $type)
    {
        $query->whereHas('faq_type', function($query) use($type) {
            $query->where('slug', $type);
        });
    }
}
