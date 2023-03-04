<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Address extends Model
{
    use HasFactory,Translatable;
    protected $translatedAttributes=['address_name','building','note'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='addresses';
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
