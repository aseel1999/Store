<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;


use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;


class OrderProductController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);

       
    }
    public function index(Request $request){
        if($request->has('order_id')){
            $order=Order::where('id','=',$request->input('order_id'))->get();
            $detailsorders = OrderProduct::with(['order' ,'product'])->where('order_id','=',$request->input('order_id'))->get();
            
    }

}