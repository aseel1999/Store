<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);
       
    }
    public function showCheckout(Request $request){
        if(Auth::check()){
            $addresses = Address::with('city','country')->where('user_id' , Auth::id())->get();
            $user=User::where('id',Auth::id())->first();
            $order=Order::with('user')->where('user_id',Auth::id())->first();
            $products_total=Cart::where('user_id',Auth::id())->sum(DB::raw('quantity * price'));
            return  view('website.checkout.checkout-user',[
                'addresses'=>$addresses,
                'order'=>$order,
                'products_total'=>$products_total,
            
            ]);
        }
        else{
            $countries=Country::all();
           return view('website.checkout.check',compact('countries'));
        }
        

    }
    


}