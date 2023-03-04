<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;


use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);

       
    }
    public function ProductDetails($id){
        $products=Product::get();
        $product = Product::findOrFail($id);
        return view('website.product.product_details',compact('products','product'));
    }

    

}
