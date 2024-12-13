<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "order_managements";
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_services', 'order_id', 'service_id');
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
