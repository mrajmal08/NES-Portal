<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class VendorPurchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "vendor_purchases";
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_products', 'purchase_id', 'product_id')->withPivot('qty', 'remarks', 'price');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'purchase_services', 'purchase_id', 'service_id')->withPivot('qty', 'remarks', 'price');
    }
}
