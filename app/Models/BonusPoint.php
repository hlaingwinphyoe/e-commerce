<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPoint extends Model
{
    use HasFactory;

    protected $table = 'bonuspoints';

    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function scopeFilterOn($query)
    {
        if(request('type')) {
            $query->whereHas('status', function ($query) {
                $query->where('slug', request('type'));
            });
        }

        if(request('role')) {
            $query->where('role_id', request('role'));
        }

        if(request('points')) {
            $query->where('points', request('points'));
        }
    }
}
