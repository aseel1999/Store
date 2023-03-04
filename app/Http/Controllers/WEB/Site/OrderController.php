<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;
use App\Models\PromoCode;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);

       
    }
    public function showOrders(Request $request){
        if(auth::check()){
        $orders=Order::where('user_id' ,'=' ,$request->user()->id)->get();
        return view('website.order.my_order',[
            'orders'=>$orders
        ]);
    }
}
public function orderDetails($id){
    $order=Order::with('user')->where('id',$id)->where('user_id',Auth::id())->first();
    $orderdetails=OrderProduct::with(['order' ,'product'])->where('order_id',$id)->orderBy('id','DESC')->get();
    $address=Address::where('id',$order->address_id)->first();
    $products_total=Cart::where('user_id',Auth::id())->sum(DB::raw('quantity * price'));
    return view('website.order.order_details',[
        'orderdetails'=>$orderdetails,
        'order'=>$order,
        'address'=>$address,
        'products_total'=>$products_total
    ]);

}
public function CouponApply(Request $request){

    $promo_code = PromoCode::where('name',$request->name)->where('end_date','>=',Carbon::now()->format('Y-m-d'))->first();
    
    if ($promo_code) {
        Session::put('promo_code',[
            'name' => $promo_code->name, 
            'amount' => $promo_code->amount,
            'products_total' =>Cart::total(),
            'discount_amount' => round(Cart::total() * $promo_code->amount/100), 
            'total_amount' => round(Cart::total() - Cart::total() * $promo_code->amount/100 ),
            
        ]);

        return response()->json(array(
            'validity' => true,                
            'success' => 'Coupon Applied Successfully'

        ));


    } else{
        return response()->json(['error' => 'Invalid Coupon']);
    }

}
public function CouponCalculation(){

    if (Session::has('promo_code')) {
        
        return response()->json(array(
         'products_total' => Cart::total(),
         'name' => session()->get('promo_code')['name'],
         'amount' => session()->get('promo_code')['amount'],
         'discount_amount' => session()->get('promo_code')['discount_amount'],
         'total_amount' => session()->get('promo_code')['total_amount'], 
        ));
    }else{
        return response()->json(array(
            
            'total'=>Cart::total(),
            
        ));
    } 
}
public function CheckoutStore(Request $request){
    $roles = [
        'user_name'=>'required',
        'user_email'=>'required',
        'user_phone'=>'required',
        'country_id'=>'required',
        'city_id'=>'required',
        'street'=>'required',
        'block'=>'required',
        'house_number'=>'required',
        'accomendation_type'=>'required',
        'delivery_mobile'=>'required',
        'extra_directions'=>'required',
        'promo_code_name'=>'required',
        'promo_code_amount'=>'required',
        'payment_method'=>'required',
    ];

    $this->validate($request, $roles);
    $item= new Order();
    $item->user_name=$request->user_name;
    $item->user_email=$request->user_email;
    $item->user_phone=$request->user_phone;
    $item->country_id=$request->country_id;
    $item->city_id=$request->city_id;
    $item->street=$request->street;
    $item->block=$request->block;
    $item->house_number=$request->house_number;
    $item->delivery_mobile=$request->delivery_mobile;
    $item->accomendation_type=$request->accomendation_type;
    $item->extra_directions=$request->extra_directions;
    $item->promo_code_name=$request->promo_code_name;
    $item->promo_code_amount=$request->promo_code_amount;
    $item->payment_method=$request->payment_method;
    $item->save();
    
    

}
public function CheckoutCreate($id){
    
    $countries=Country::get();
    return view('website.checkout.check',compact('item','countries'));
}

}