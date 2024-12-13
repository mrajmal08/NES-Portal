<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "mechanics";
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

