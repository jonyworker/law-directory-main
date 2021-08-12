<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovKind extends Model
{
    use HasFactory;
    protected $table = "government_product_kind";
    public $timestamps = false;
    
   // protected $table = "products_quality";
    protected $guarded = [];
    
    
}
