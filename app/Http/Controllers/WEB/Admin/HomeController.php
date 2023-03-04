<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\Notification;
use App\Models\Order;

use App\Models\Contact;
use App\Models\Category;
class HomeController extends Controller
{

    public function index()
    {
        $products_count =Product::count();
        $admins=Admin::count();
       $sizes=Size::count();
        $colors = Color::count();
        $contacts = Contact::count();
        $countries=Country::count();
        $cities=City::count();
        $categories =Category::count();
        
        return view('admin.home.dashboard')->with(compact('products_count',
            'admins','sizes'
        ,'colors','contacts','countries','cities','categories'
        ));
    } 


    public function changeStatus($model, Request $request)
    {
        $role = "";
        if ($model == "admins") $role = 'App\Models\Admin';
        if ($model == "categories") $role = 'App\Models\Category';
        if ($model == "products") $role = 'App\Models\Product';
        if ($model == "colors") $role = 'App\Models\Color';
        if ($model == "sizes") $role = 'App\Models\Size';
        if ($model == "countries") $role = 'App\Models\Country';
        if ($model == "cities") $role = 'App\Models\City';
        if ($model == "contacts") $role = 'App\Models\Contact';
        if ($model == "users") $role = 'App\Models\User';
        if ($model == "roles") $role = 'App\Models\Role';
        if ($model == "brands") $role = 'App\Models\Brand';
        if ($model == "promo_codes") $role = 'App\Models\PromoCode';
        if ($model == "orders") $role = 'App\Models\Order';
        if ($model == "notifications") $role = 'App\Models\Notification';
       
        if ($role != "") {
            if ($request->action == 'delete') {
                $role::query()->whereIn('id', $request->IDsArray)->delete();
            } else {
                if ($request->action) {
                    $role::query()->whereIn('id', $request->IDsArray)->update(['status' => $request->action]);
                }
            }

            return $request->action;
        }
        return false;


    }


}
