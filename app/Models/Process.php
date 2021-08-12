<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;
    protected $table = "products_process";
    public $timestamps = false;

    
    protected $guarded = [];
    
    public function process()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
    public function getUnansweredQuestionCountAttribute() {
        return count(array_filter($this->attributes));
    }
    public function getAnsweredQuestionCountAttribute() {
        return count($this->attributes);
    }
}
