<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    public $quantity=1;
    use HasFactory,Translatable,SoftDeletes;
    protected $translatedAttributes=['name','description'];
    protected $hidden = ['translations' ,'updated_at'];
    protected $table='products';
    public function scopeFilter($query)
    {
        if (request()->has('name')) {
            if (request()->get('name') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('name', '%' . request()->get('name') . '%');
                });
        }
        
        if (request()->has('id')) {
            if (request()->get('id') != null)
                $query->where(function ($q) {
                    $q->whereTranslationLike('id', '%' . request()->get('id') . '%');
                });
        }

    }
    public function category()
    {
        return $this->belongsTo(
            Category::class, 
            'category_id', 
            'id'
        );
    }
    
    public function user()
    {
        return $this->belongsTo(
            User::class, 
            'user_id', 
            'id' 
        );
    }
    
    public function getImageAttribute($image)
    {
        return !is_null($image) ? url('uploads/images/products/' . $image) : url('uploads/images/d.jpg');
    }
    public function images()
    {
        return $this->morphMany(ProductImage::class, 'object', 'object_type', 'object_id', 'id');
    }
    public function sizesForColor($colorId)
   {
    return $this->hasManyThrough(Size::class, ProductColorSize::class, 'product_id', 'id', 'id', 'size_id')
                ->where('color_id', $colorId)
                  ->get();
   }
    public function colors()
    {
        return $this->belongsToMany(Color::class, ProductColorSize::class,'product_id','color_id');
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, ProductColorSize::class,'product_id','size_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    
    


    
    



}
