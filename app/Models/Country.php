<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Country extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes=['country_name'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='countries';
    public function cities(){
        return $this->hasMany(City::class);
    }
    public function scopeFilter($query)
    {
        if (request()->has('country_name')) {
            if (request()->get('country_name') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('country_name', '%' . request()->get('country_name') . '%');
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
