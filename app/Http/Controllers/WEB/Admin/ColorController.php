<?php

namespace App\Http\Controllers\WEB\Admin;
use App\Models\Color;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Language;
use App\Models\Setting;
class ColorController extends Controller
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
                if(can(['colors-show' , 'colors-create' , 'colors-edit' , 'colors-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'create' || $route_name== 'store'){
                if(can('colors-create')){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('colors-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('colors-delete')){
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
        $items = Color::orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        return view('admin.colors.home', [
            'items' =>$items,
        ]);
    }
    public function create()
    {
       
        return view('admin.colors.create');
    }
    public function store(Request $request)
    {
       
        {
            $roles = [
            ];
            $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_color_' . $locale] = 'required';
            
        }
           $this->validate($request, $roles);
           
            $item= new Color();
            foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name_color= $request->get('name_color_' . $locale);
           
        } 
            $item->save();
            activity($item->name_color)->causedBy(auth('admin')->user())->log(' إضافة لون  جديد');
            return redirect()->back()->with('status', __('cp.create'));
        }
        
    }
    public function edit($id){
            $item = Color::where('id',$id)->first();
            return view('admin.colors.edit', [
                'item' => $item,
            ]);

        }
        public function update(Request $request, $id)
    {
        $roles = [
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_color_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $item = Color::query()->findOrFail($id);
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name_color = $request->get('name_color_' . $locale);
            
        }
        $item->save();
        activity($item->name_color)->causedBy(auth('admin')->user())->log(' تعديل  اللون');

       
        return redirect()->back()->with('status', __('cp.update'));
    }
    public function destroy($id)
    {
        
        $ad = Color::query()->findOrFail($id);
        if ($ad) {
            Color::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
}
