<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
class City extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes=['city_name'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='cities';
    protected $fillable=['country_id'];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function scopeFilter($query)
    {
        if (request()->has('city_name')) {
            if (request()->get('city_name') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('city_name', '%' . request()->get('city_name') . '%');
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
