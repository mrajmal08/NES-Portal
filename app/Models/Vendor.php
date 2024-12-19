<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "vendors";
    protected $guarded = [];

    public function vendors()
    {
        return $this->hasMany(VendorPurchase::class);
    }

    public function histories()
    {
        return $this->hasMany(VendorHistory::class);
    }
}
