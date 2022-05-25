<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function pricings()
    {
        return $this->hasMany(Pricing::class, 'role_id', 'id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class, 'role_id', 'id');
    }

    public function bonuspoints()
    {
        return $this->hasMany(Bonuspoint::class, 'role_id', 'id');
    }

    //helper functions   

    public function hasPermission($permis)
    {
        $bool = false;
        foreach ($this->permissions as $permission) {
            if ($permission->slug == $permis) {
                $bool = true;
            }
        }
        return $bool;
    }

    public function hasPermissions($permissions)
    {
        foreach ($permissions as $perm) {
            if ($this->hasPermission($perm)) {
                return true;
            }
        }
    }


    //scope functions
    public function scopeIsType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeNotSeller($query)
    {
        $query->whereNotIn('name', ['Admin', 'Operator', 'Manager', 'Owner', 'Technician']);
    }

    public function scopeNotDeveloper($query)
    {
        $query->whereNotIn('name', ['technician']);
    }

    public function scopeNotAdmin($query)
    {
        $query->whereNotIn('name', ['technician', 'admin']);
    }

    public function scopeNotMyself($query)
    {
        $query->where('id', '!=', auth()->user()->role_id);
    }

    public function scopeFilterOn($query)
    {
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }
    }
}