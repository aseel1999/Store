<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Country;

class CountryController extends Controller
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
                if(can(['countries-show' , 'countries-create' , 'countries-edit' , 'countries-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'create' || $route_name== 'store'){
                if(can('countries-create')){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('countries-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('countries-delete')){
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
       
        $items = Country::filter()->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        return view('admin.countries.home', [
            'items' =>$items,
            
        ]);
    }


    public function create()
    {
       
        return view('admin.countries.create');
    }


    public function store(Request $request)
    {
        $roles = [
            
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['country_name_' . $locale] = 'required';
            
        }
        $this->validate($request, $roles);

        $item= new Country();
       
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->country_name = $request->get('country_name_' . $locale);
            
        } 
        
        $item->save();
        activity()->causedBy(auth('admin')->user())->log(' إضافة  دولة جديدة');
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
       
        $item = Country::where('id',$id)->first();
        return view('admin.countries.edit', [
            'item' => $item,
            
        ]);
    }

    public function update(Request $request, $id)
    {
        
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['country_name_' . $locale] = 'required';
            
        }
        $this->validate($request, $roles);

        $item = Country::query()->findOrFail($id);
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->country_name = $request->get('country_name_' . $locale);
            
        }
        

        activity()->causedBy(auth('admin')->user())->log('تعديل  الدول');

        $item->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    

    public function destroy($id)
    {
        //
        $ad = Country::query()->findOrFail($id);
        if ($ad) {
            Country::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

}
