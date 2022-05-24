<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliFee extends Model
{
    use HasFactory;

    protected $table = 'deli_fees';

    protected $guarded = [];

    public function townships()
    {
        return $this->belongsToMany(Township::class, 'deli_township', 'deli_fee_id',  'township_id');
    }

    //helper funcitons
    public function hasTownship($id)
    {
        $bool = false;

        foreach($this->townships as $township) {
            if($township->id == $id) {
                $bool = true;
            }
        }
        return $bool;
    }
}
