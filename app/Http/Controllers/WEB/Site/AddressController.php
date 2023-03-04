<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;
use App\Models\Token;
use App\Models\Address;
use App\Models\User;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;


class AddressController extends Controller
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
            if(can('addresses')){
                return $next($request);
            }
            if($route_name== 'index' ){
                if(can(['addresses-show' , 'addresses-create' ,  'addresses-edit' , 'addresses-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('addresses-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('addresses-delete')){
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
         $countries=Country::all();
         $cities=City::all();
          $addresses = Address::with('city','country')->where('user_id' , $request->user()->id)->get();
          return view('website.address.my_address', [
            'addresses' => $addresses,
            'cities'=>$cities,
            'countries'=>$countries,
            
        ]);
       
    
    }

    public function create()
    {
    
    }

    public function store(Request $request)
    {
     
            $roles = [
                'city_id'=>'required',
                'country_id'=>'required',
             
            ];
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $roles['address_name_' . $locale] = 'required';
                $roles['building_' . $locale] = 'required';
                $roles['note_' . $locale] = 'nullable';
                
            }
           // $this->validate($request, $roles);
          //  return $request;
            $item= new Address();
            $item->city_id=$request->city_id;
            $item->country_id=$request->country_id;
          
            foreach ($locales as $locale)
            {
                $item->translateOrNew($locale)->address_name = $request->get('address_name_' . $locale);
                $item->translateOrNew($locale)->building = $request->get('building_' . $locale);
                $item->translateOrNew($locale)->note = $request->get('note_' . $locale);
            }
           // $item->save();

          $isSaved = $request->user()->addresses()->save($item);
            
           return response()->json(
               ['message' => $isSaved ? 'Saved successfully' : 'Save failed!' ,'id'=>$item->id],
               $isSaved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
            );
    

    }


    public function edit($id)
    {

        $item = Address::findOrFail($id);
       // return view('website.address.model', [
       //     'item' => $item,
        //]);
    }

    public function update(Request $request, $id)
    {
         $roles = [
            'city_id'=>'required',
            'country_id'=>'required',
           
            
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
                $roles['address_name_' . $locale] = 'required';
                $roles['building_' . $locale] = 'required';
                $roles['note_' . $locale] = 'nullable';
          
        }
        $this->validate($request, $roles);
        $item = Address::query()->findOrFail($id);
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->address_name = $request->get('address_name_' . $locale);
            $item->translateOrNew($locale)->building = $request->get('building_' . $locale);
            $item->translateOrNew($locale)->note = $request->get('note_' . $locale);
        }
        $isSaved = $request->user()->addresses()->save($item);

        return response()->json(
            ['message' => $isSaved ? 'Saved successfully' : 'Save failed!' ,'id'=>$item->id],
            $isSaved ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
    public function destroy($id)
    {
        $item = Address::query()->findOrFail($id);
        if ($item) {
            Address::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }

}
