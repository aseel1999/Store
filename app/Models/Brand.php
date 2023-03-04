<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Translatable;

class Brand extends Model
{
    use HasFactory,Translatable,SoftDeletes;
    protected $translatedAttributes=['brand_name'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='brands';
    public function scopeFilter($query)
    {
        if (request()->has('brand_name')) {
            if (request()->get('brand_name') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('brand_name', '%' . request()->get('brand_name') . '%');
                });
        }
        
        if (request()->has('id')) {
            if (request()->get('id') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('id', '%' . request()->get('id') . '%');
                });
        }

    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    
    

}
