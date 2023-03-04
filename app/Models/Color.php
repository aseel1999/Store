<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Color extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes=['name_color'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='colors';
    public function scopeFilter($query)
    {
        if (request()->has('name_color')) {
            if (request()->get('name_color') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('name_color', '%' . request()->get('name_color') . '%');
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
