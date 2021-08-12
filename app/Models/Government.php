<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    use HasFactory;
    protected $table = "government";
    public $timestamps = false;
    
  
    
   // protected $table = "products_quality";
    protected $guarded = [];
    
    public function gov()
    {
        return $this->belongsTo(Products::class,'product_id');
    }
    
  
    
    public function setPackagingAttribute($packaging)
    {
        if (is_array($packaging)) { $this->attributes['packaging'] = json_encode($packaging); }
    }
    public function getPackagingAttribute($packaging)
    {
        return json_decode($packaging, true);
    }
    
    
    public function setIngredientsChemicalAttribute($ingredients_chemical)
    {
        if (is_array($ingredients_chemical)) { $this->attributes['ingredients_chemical'] = json_encode($$ingredients_chemical); }
    }
    public function getIngredientsChemicalAttribute($ingredients_chemical)
    {
        return json_decode($ingredients_chemical, true);
    }
    
    
    public function setPoisonInfoAttribute($poison_info)
    {
        if (is_array($poison_info)) { $this->attributes['poison_info'] = json_encode($poison_info); }
    }
    public function getPoisonInfoAttribute($poison_info)
    {
        return json_decode($poison_info, true);
    }  
    
    
    public function setFunctionReportAttribute($function_report)
    {
        if (is_array($function_report)) { $this->attributes['function_report'] = json_encode($function_report); }
    }
    public function getFunctionReportAttribute($function_report)
    {
        return json_decode($function_report, true);
    }
    
    
    public function setSafetyReportAttribute($safety_report)
    {
        if (is_array($safety_report)) { $this->attributes['safety_report'] = json_encode($safety_report); }
    }
    public function getSafetyReportAttribute($safety_report)
    {
        return json_decode($safety_report, true);
    }
    
    
    public function setContainerReportAttribute($container_report)
    {
        if (is_array($container_report)) { $this->attributes['container_report'] = json_encode($container_report); }
    }
    public function getContainerReportAttribute($container_report)
    {
        return json_decode($container_report, true);
    }
    
}
