<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
//    protected $primaryKey = 'Code';
    protected $guarded = [];
    
    public function controls()
    {
        return $this->hasMany(Quality::class,'product_id');
    }
    public function purchase()
    {
        return $this->hasMany(Purchase::class,'product_id');
    }
    public function ingredients()
    {
        return $this->hasMany(Ingredients::class,'product_id');
    }
    public function government()
    {
        return $this->hasOne(Government::class,'product_id');
    }
    
    /*
    public function getIngredientListAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setIngredientListAttribute($value)
    {
        $this->attributes['ingredient_list'] = json_encode(array_values($value));
    }*/
    
}
