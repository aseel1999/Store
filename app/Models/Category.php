<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes,Translatable;
    protected $translatedAttributes=['name'];


    protected $hidden = ['translations' ,'updated_at','deleted_at'];
    public function getImageAttribute($image)
    {
        return !is_null($image) ? url('uploads/images/categories/' . $image) : url('uploads/images/d.jpg');
    }
    public function products(){
         return $this->hasMany(Product::class);
    }
    public function scopeFilter($query)
    {
        if (request()->has('name')) {
            if (request()->get('name') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('name', '%' . request()->get('name') . '%');
                });
        }
    }

    
}
