<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    use HasFactory;
    protected $table='product_color_sizes';
    public function products()
  {
    return $this->belongsToMany(
         Product::class,
         'product_id'
     );
   }
}
