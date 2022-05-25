<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'image',
        'subscribed',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function userpoints()
    {
        return $this->hasMany(UserPoint::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * scope functions
     */
    public function scopeFilterOn($query)
    {
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if(request('role')) {
            $query->where('role_id', request('role'));
        }
    }

    public function scopeIsType($query, $type)
    {
        $query->whereHas('role', function($query) use($type){
            $query->where('type', $type);
        });
    }

    public function scopeNotAdmin($query)
    {
        $query->whereHas('role', function ($q) {
            $q->whereNotIn('slug', ['admin', 'technician']);
        });
    }

    public function scopeIsAdmin($query)
    {
        $query->whereHas('role', function ($q) {
            $q->whereIn('slug', ['admin', 'technician']);
        });
    }

    public function scopeIsSeller($query)
    {
        $query->whereHas('role', function ($q) {
            $q->whereIn('slug', ['admin', 'operator', 'manager']);
        });
    }

    public function scopeNotSeller($query)
    {
        $query->whereHas('role', function($q) {
            $q->where('type', 'Customer');
        });
    }

    public function isBuyer()
    {
        return $this->role->type == 'Customer';
    }

    public function hasRoles($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        return $this->role->slug == $role ? true : false;
    }

    public function hasPermission($perm)
    {
        $this->role->hasPermission($perm);
    }

    public function scopeHavePermissions($query, $perms)
    {
        $query->whereHas('role', function ($q) use ($perms) {
            $q->whereHas('permissions', function ($q) use ($perms) {
                $q->whereIn('slug', $perms);
            });
        });
    }

    public function getThumbnailAttribute()
    {
        $img = $this->medias()->first();

        $path = asset('images/user.png');

        if ($img) {
            if (Storage::exists('public/thumbnail/' . $img->slug)) {
                $path = Storage::url('public/thumbnail/' . $img->slug);
            } else {
                $path = $img->url;
            }
        } 
        
        return $path;
    }

    public function getImage()
    {

        $image = $this->medias()->first();

        if ($image) {
            // $img = $image->url;
            $img = Storage::url('public/thumbnail/' . $image->slug);
        } else {
            $img = url('images/user.png');
        }

        return $img;
    }

    public function getPointsAttribute()
    {
        $in_points = $this->userpoints()->whereHas('status', function($q) {
            $q->where('slug', 'in');
        })->pluck('points')->sum();

        $used_points = $this->userpoints()->whereHas('status', function($q) {
            $q->where('slug', 'out');
        })->pluck('points')->sum();

        return $in_points - $used_points;
    }

    public function getAddressAttribute()
    {
        return $this->addresses->count() ? $this->addresses()->first()->name : '';
    }
}
