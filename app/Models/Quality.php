<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;
    protected $table = "products_quality";
    protected $guarded = [];
    
    public function control()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
}
