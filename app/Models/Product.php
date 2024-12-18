<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $guarded = [];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id')->withPivot('qty', 'remarks');
    }

    public function vendors()
    {
        return $this->hasMany(VendorPurchase::class);
    }

}
