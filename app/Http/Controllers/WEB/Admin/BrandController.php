<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Brand;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->locales = Language::all();
        $this->settings = Setting::query()->first();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);


        $route=Route::currentRouteAction();
        $route_name = substr($route, strpos($route, "@") + 1);
        $this->middleware(function ($request, $next) use($route_name){

            if($route_name== 'index' ){
                if(can(['brands-show' , 'brands-create' , 'brands-edit' , 'brands-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'create' || $route_name== 'store'){
                if(can('brands-create')){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('brands-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('brands-delete')){
                    return $next($request);
                }
            }else{
                return $next($request);
            }
            return redirect()->back()->withErrors(__('cp.you_dont_have_permission'));
        });

    }
    public function index()
    {
       
       $items = Brand::query()->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
      
        return view('admin.brands.home', [
            'items' =>$items,
            
        ]);
    }


    public function create()
    {
       
        return view('admin.brands.create',[
            
        ]);
    }


    public function store(Request $request)
    {
        $roles = [
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['brand_name_' . $locale] = 'required';
            
        }
        $this->validate($request, $roles);

        $item= new Brand();
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->brand_name = $request->get('brand_name_' . $locale);
            
        } 
        
        $item->save();
        activity()->causedBy(auth('admin')->user())->log(' إضافة  علامة تجارية جديد ');
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
       
        $item = Brand::where('id',$id)->first();
        
        return view('admin.brands.edit', [
            'item' => $item,
            
        ]);
    }

    public function update(Request $request, $id)
    {
        $roles = [
            
          
            
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['brand_name_' . $locale] = 'required';
            
        }
        $this->validate($request, $roles);

        $item = Brand::query()->findOrFail($id);
       
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->brand_name = $request->get('brand_name_' . $locale);
            
        }
        

        activity()->causedBy(auth('admin')->user())->log('تعديل  العلامة التجارية');

        $item->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    

    public function destroy($id)
    {
        //
        $ad = Brand::query()->findOrFail($id);
        if ($ad) {
            Brand::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

}
