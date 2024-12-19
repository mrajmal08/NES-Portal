<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "purchase_products";
    protected $guarded = [];
}
