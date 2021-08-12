<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
    use HasFactory;
    protected $table = "ingredient";
    public $timestamps = false;

    
    protected $guarded = [];
    
    public function process()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
        
}
