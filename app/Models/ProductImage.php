<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    public function getImageAttribute($image)
    {
        return !is_null($image) ? url('uploads/images/products/' . $image) : url('uploads/images/d.jpg');
    }
}
