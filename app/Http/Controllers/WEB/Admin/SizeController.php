<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Exports\UsersExport;
use App\Models\Setting;
use App\Models\Token;
use App\Models\Size;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);

        $route=Route::currentRouteAction();
        $route_name = substr($route, strpos($route, "@") + 1);
        $this->middleware(function ($request, $next) use($route_name){
            if(can('sizes')){
                return $next($request);
            }
            if($route_name== 'index' ){
                if(can(['sizes-show' , 'sizes-create' ,  'sizes-edit' , 'sizes-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('sizes-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('sizes-delete')){
                    return $next($request);
                }
            }else{
                return $next($request);
            }
            return redirect()->back()->withErrors(__('cp.you_dont_have_permission'));
        });
    }

    public function index(Request $request)
    {
        $items = Size::query()->filter()->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        return view('admin.sizes.home', [
            'items' => $items,
        ]);


    }

    public function create()
    {
       return view('admin.sizes.create');
    }


    


    

    public function store(Request $request)
    {
        {
            $roles = [
                
                
            ];
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $roles['name_size_' . $locale] = 'required';
                
            }
            $this->validate($request, $roles);
    
            $item= new Size();
            
            
    
            foreach ($locales as $locale)
            {
                $item->translateOrNew($locale)->name_size = $request->get('name_size_' . $locale);
                
            } 
            
            $item->save();
            activity($item->name_size)->causedBy(auth('admin')->user())->log(' إضافة حجم  جديد ');
            return redirect()->back()->with('status', __('cp.create'));
        }

    }


    public function edit($id)
    {

        $item = Size::findOrFail($id);
        return view('admin.sizes.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
         $roles = [
            
            
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_size_' . $locale] = 'required';
          
        }
        $this->validate($request, $roles);

        $item = Size::query()->findOrFail($id);
        
       

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name_size = $request->get('name_size_' . $locale);
            
        
        }
        activity($item->name_size)->causedBy(auth('admin')->user())->log(' تعديل الحجم ');

        $item->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


    


    
    public function destroy($id)
    {
        $item = Size::query()->findOrFail($id);
        if ($item) {
            Size::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }

}
