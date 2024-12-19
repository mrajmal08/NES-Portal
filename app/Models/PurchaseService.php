<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PurchaseService extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "purchase_services";
    protected $guarded = [];
}
