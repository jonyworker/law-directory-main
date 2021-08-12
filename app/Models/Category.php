<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $guarded = [];
  //  protected $primaryKey = 'erp_id';
    public $timestamps = false;
    public $incrementing = false;

    public function scopeLarge()
    {
        return $this->where('type', 1)->orderby('id','asc');
    }

    public function scopeMid()
    {
        return $this->where('type', 2)->orderby('id','asc');
    }

    public function scopeSmall()
    {
        return $this->where('type', 3)->orderby('id','asc');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function brothers()
    {
        return $this->parent->children();
    }

    public static function options($id)
    {
        if (! $self = static::find($id)) {
            return [];
        }
        return $self->brothers()->pluck('name', 'id');
    }
}