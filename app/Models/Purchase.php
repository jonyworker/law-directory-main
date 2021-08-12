<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $table = "products_purchase";
    protected $guarded = [];
    public $timestamps = false;
    
    public function purchase()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
