<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;
use App\Traits\imageTrait;
use Intervention\Image\Facades\Image;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductColorSize;
use Carbon\Carbon;



class ProductController extends Controller
{
    use imageTrait;
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,

        ]);

         $route=Route::currentRouteAction();
         $route_name = substr($route, strpos($route, "@") + 1);
         $this->middleware(function ($request, $next) use($route_name){
            if(can('products')){
                return $next($request);
             }
          if($route_name== 'index' ){
             if(can(['products-show' , 'products-create' , 'products-edit' , 'products-delete'])){
                 return $next($request);
             }
          }elseif($route_name== 'create' || $route_name== 'store'){
              if(can('products-create')){
                 return $next($request);
             }
          }elseif($route_name== 'edit' || $route_name== 'update'){
              if(can('products-edit')){
                 return $next($request);
             }
          }elseif($route_name== 'destroy' || $route_name== 'delete'){
              if(can('products-delete')){
                 return $next($request);
             }
          }else{
              return $next($request);
          }
          if($request->ajax()){
            $message = __('cp.you_dont_have_premession');
            return response()->json(['status' => false, 'code' => 503, 'message' => $message, ]);
          }else{
            return redirect()->back()->withErrors(__('cp.you_dont_have_premession'));
          }
        });
    }

    public function index(Request $request)
    {
        
        $items =Product::with('category','brand')->filter()->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        
        return view('admin.products.home', [
            'items' =>$items,
            
        ]);

    }


    public function create()
    {
        
        $categories=Category::get();
        $colors=Color::get();
        $sizes=Size::get();
        $brands=Brand::latest()->get();
        
        return view('admin.products.create', [
            'brands'=>$brands,
            'categories'=>$categories,
            'colors'=>$colors,
            'sizes'=>$sizes
            
        ]);}


    public function store(Request $request)
    {
        
        $roles = [
            'image' => 'image|mimes:jpeg,jpg,png,gif',
            'category_id'=>'required',
            'brand_id'=>'required',
            'price'=>'required',
            'discount'=>'required',
            'colors'=>'required',
            'sizes'=>'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
            $roles['description_' . $locale] = 'required';
        }
        $this->validate($request, $roles);
        $item= new Product();
        $item->category_id=$request->category_id;
        $item->brand_id=$request->brand_id;
        $item->price=$request->price;
        $item->discount=$request->discount;
        $item->image=$request->image;
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name = $request->get('name_' . $locale);
            $item->translateOrNew($locale)->description = $request->get('description_' . $locale);
        } 
        if ($request->hasFile('image') && $request->image != '') {
            $item->image = $this->storeImage($request->image, 'products');
        }
        
        
        
        $item->save();
        if($request->has('filename')  && !empty($request->filename))
        {
            foreach($request->filename as $one)
            {
                if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = "" .str_random(8) . "" .  "" . time() . "" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name. ".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/news/$newName");
                    }
                    $image=new ProductImage();
                    $image->product_id=$item->id;
                    $image->image = $newName;
                    $image->url='products/'.$newName;
                    $item->images()->save($image);
                }
            }
        }
        if ($request->colors != null) {
            foreach ($request->colors as $color_id) {
              $color_sizes = $request->input("sizes_for_color_$color_id");
              if ($color_sizes != null) {
                foreach ($color_sizes as $size_id){
                  $quantity = $request->input("quantities_for_color_$color_id" . "_size_" . "$size_id");
                  $values[] = [
                    'product_id' => $item->id,
                    'color_id' => $color_id,
                    'size_id' => $size_id,
                    'quantity' => $quantity,
                  ];
                }
              }
            }
            ProductColorSize::insert($values);
        }
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
        {
           
        $product= Product::where('id',$id)->first();
        $categories=Category::get();
        $brands=Brand::get();
        $colors=Color::get();
        $sizes=Size::get();
            return view('admin.products.edit', [
                'product' => $product,
               'brands'=>$brands,
                'categories'=>$categories,
                'colors'=>$colors,
                'sizes'=>$sizes,
                
            ]);

        }


    public function update(Request $request, $id)
    {
        
        $roles = [
            'image' => 'image|mimes:jpeg,jpg,png,gif',
            'category_id'=>'required',
            'brand_id'=>'required',
            'price'=>'required',
            'discount'=>'required',
            'colors'=>'required',
            'sizes'=>'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
            $roles['description_' . $locale] = 'required';
        }
        $this->validate($request, $roles);
        $item = Product::query()->findOrFail($id);
       $item->category_id=$request->category_id;
       $item->brand_id=$request->brand_id;
       $item->price=$request->price;
       $item->discount=$request->discount;
        foreach ($locales as $locale)
        {
        $item->translateOrNew($locale)->name = $request->get('name_' . $locale);
        $item->translateOrNew($locale)->description= $request->get('description_' . $locale);
        }
        if ($request->hasFile('image') && $request->image != '') {
            $item->image = $this->storeImage($request->image, 'products' , $item->getRawOriginal('image') );
        }
        $item->save();
       
        $imgsIds = $item->images->pluck('id')->toArray();
        $newImgsIds = ($request->has('oldImages'))? $request->oldImages:[];
        $diff = array_diff($imgsIds,$newImgsIds);
        ProductImage::whereIn('id',$diff)->delete();

        if($request->has('filename')  && !empty($request->filename))
        {
            foreach($request->filename as $one)
            {
                if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = "" .str_random(8) . "" .  "" . time() . "" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name. ".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/news/$newName");
                    }
                    $image=new ProductImage();
                    $image->product_id=$item->id;
                    $image->image = $newName;
                    $image->url='products/'.$newName;
                    $item->images()->save($image);
                }
            }
        }
        ProductColorSize::where('product_id', $item->id)->delete();

            if ($request->colors != null) {
                foreach ($request->colors as $color_id) {
                  $color_sizes = $request->input("sizes_for_color_$color_id");
                  if ($color_sizes != null) {
                    foreach ($color_sizes as $size_id){
                      $quantity = $request->input("quantities_for_color_$color_id" . "_size_" . "$size_id");
                      $values[] = [
                        'product_id' => $item->id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'quantity' => $quantity,
                      ];
                    }
                  }
                }
                ProductColorSize::insert($values);
              }
    
        
        
        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy(Product $product)
    {
        if ($product) {
            $isDelete=$product->delete();
            if($isDelete){
                $this->deleteImages($product);
                $this->deleteColorsSizes($product);
                return "success";

            }
        }
        return "fail";
    }
    private function deleteImages(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::delete('public/web/products/' .$image->name);
            $image->delete();
        }
    }
    private function deleteColorsSizes(Product $product)
    {
        $productColorsSizes= ProductColorSize::where('product_id' , $product->id)->get();

        foreach ($productColorsSizes as $productColorSize) {
           
            $productColorSize->delete();
        }
    }

    
}