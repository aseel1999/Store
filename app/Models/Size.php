<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Size extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes=['name_size'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='sizes';
    public function productColorSizes()
    {
        return $this->hasMany(ProductColorSize::class, 'size_id','id');
    }
    public function scopeFilter($query)
    {
        if (request()->has('name_size')) {
            if (request()->get('name_size') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('name_size', '%' . request()->get('name_size') . '%');
                });
        }
        
        if (request()->has('id')) {
            if (request()->get('id') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('id', '%' . request()->get('id') . '%');
                });
        }

    }
}
