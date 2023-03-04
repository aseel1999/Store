<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\imageTrait;
use App\Models\Language;
use Intervention\Image\Facades\Image;
use App\Models\Country;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
class CityController extends Controller
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
        if(can('cities')){
            return $next($request);
         }
          if($route_name== 'index' ){
             if(can(['cities-show' , 'cities-create', 'cities-edit','cities-delete'])){
                 return $next($request);
             }
             }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('cities-edit')){
                   return $next($request);
               }
          }elseif($route_name== 'destroy' || $route_name== 'delete'){
              if(can('cities-delete')){
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
public function index()
    {
        $items =City::with('country')->filter()->orderBy('id', 'desc')->get();
       
        return view('admin.cities.home', [
            'items' =>$items,
            
            
        ]);
    }


    public function create()
    {
        $countries=Country::get();
        return view('admin.cities.create',[
            'countries'=>$countries,
        ]);
    }
    public function store(Request $request){
        $roles = [
            'country_id' => 'required',
            

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['city_name_' . $locale] = 'required';
           
        }
        $this->validate($request, $roles);
        $item= new City();
       

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->city_name = $request->get('city_name_' . $locale);
            
        } 
        $item->country_id=$request->country_id;
        $item->save();
        activity()->causedBy(auth('admin')->user())->log(' إضافة مدينة جديد ');
        return redirect()->back()->with('status', __('cp.create'));
        
        
    }
    public function edit($id)
        {
            
            $item = City::findOrFail($id);
            $countries=Country::get();
            return view('admin.cities.edit', [
                'item' => $item,
                'countries'=>$countries
                
            ]);

        }
        public function update(Request $request, $id)
    {
        $roles = [
            'country_id' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['city_name_' . $locale] = 'required';
            
        }
        $this->validate($request, $roles);

        $item = City::query()->findOrFail($id);

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->city_name = $request->get('city_name_' . $locale);
           
        }
        $item->country_id=$request->country_id;
        $item->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        $ad = City::query()->findOrFail($id);
        if ($ad) {
            City::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
}
