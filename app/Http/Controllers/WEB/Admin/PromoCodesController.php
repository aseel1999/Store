<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PromoCode;
use App\Models\PromoCodeUser;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
class PromoCodesController extends Controller
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
                if(can(['promo_codes-show' , 'promo_codes-create' , 'promo_codes-edit' , 'promo_codes-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'create' || $route_name== 'store'){
                if(can('promo_codes-create')){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('promo_codes-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('promo_codes-delete')){
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
        $items = PromoCode::filter()->orderBy('id', 'desc')->get();
        return view('admin.promo_codes.home', [
            'items' =>$items,
        ]);
    }


    public function create()
    {
       
        return view('admin.promo_codes.create');
    }


    public function store(Request $request)
    {
        $roles = [
            'name' => 'required|unique:promo_codes',
            'amount' => 'required',
            'max_usage' => 'nullable',
            'end_date' => 'required|date_format:Y-m-d|after:today',
            'user_id'=>'nullable',
            'discount_type'=>'required',
            'status'=>'required',

        ];

        $this->validate($request, $roles);

        $item= new PromoCode();

        $item->name=$request->name;
        $item->amount=$request->amount;
        $item->max_usage=$request->max_usage;
        $item->end_date=$request->end_date;
        $item->status=$request->status;
        $item->discount_type=$request->discount_type;
//        if (isset($request->user_id)&&$request->user_id > 0){
//            $item->user_id=$request->user_id;
//        }else{
//            $item->user_id=0;
//        }

        
        $item->save();
        activity($item->name)->causedBy(auth('admin')->user())->log(' إضافة كود الخصم ');

        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
        $item = PromoCode::where('id',$id)->first();
        return view('admin.promo_codes.edit', [
            'item' => $item,
            
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PromoCode::query()->where('id',$id)->first();

        $roles = [
            'name' => 'required|unique:promo_codes,name,' . $item->id,
            'amount' => 'required',
            'max_usage' => 'nullable',
            'end_date' => 'required|date_format:Y-m-d|after:today',
//            'user_id'=>'nullable',
            'discount_type'=>'required',
            'status'=>'required',

        ];

        $this->validate($request, $roles);

        $item->name=$request->name;
        $item->amount=$request->amount;
        $item->max_usage=$request->max_usage;
        $item->end_date=$request->end_date;
        $item->status=$request->status;
        $item->discount_type=$request->discount_type;
//        if (isset($request->user_id)&&$request->user_id > 0){
//            $item->user_id=$request->user_id;
//        }else{
//            $item->user_id=0;
//        }
//
//

        
        $item->save();
        activity($item->name)->causedBy(auth('admin')->user())->log(' تعديل كود الخصم ');

        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        //
        $ad = PromoCode::query()->findOrFail($id);
        if ($ad) {
            PromoCode::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

}
