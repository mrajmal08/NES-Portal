<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "services";
    protected $guarded = [];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_services', 'service_id', 'order_id')->withPivot('qty', 'remarks', 'price');
    }

    public function vendors()
    {
        return $this->hasMany(VendorPurchase::class);
    }
}
