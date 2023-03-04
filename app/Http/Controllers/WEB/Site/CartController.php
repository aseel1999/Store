<?php

namespace App\Http\Controllers\WEB\Site;
use App\Models\Setting;


use App\Models\Product;
use App\Models\Cart;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Image;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);

       
    }
    public function store(Request $request){
        
            $validator = Validator($request->all(), [
                'product_id' =>'required|numeric|exists:products,id',
                'size_id'=>'required|numeric|exists:sizes,id',
                'color_id'=>'required|numeric|exists:colors,id',
                'price' => 'required',
                'quantity' => 'required|integer',
                
    
            ]);

            if (!$validator->fails()) {
                $Product = Product::find($request->product_id);
                if (!is_null($Product)) {
                    if (!$request->user()->carts()->where('product_id', $Product->id)->exists()) {
                        $cart = new Cart();
                        $cart->product_id= $request->product_id;
                        $cart->user_id= $request->user()->id;
                        $cart->price= $request->price;
                        $cart->quantity= $request->quantity;
                        $cart->color_id= $request->color_id;
                        $cart->size_id= $request->size_id;
                        $request->session()->put('quantity', $cart->quantity);
                        $request->session()->put('price', $cart->price);
                        
                        $isSaved = $cart->save();
                        if ($isSaved){
                        $numOfProductsCart=Cart::where('user_id' , $request->user()->id)->count();
                        
                        return response()->json(['message' => 'Product cart added' ,'numOfProductsCart'=>$numOfProductsCart]);
                    }
                } 
                else {
    
                    
                    return response()->json(['message' => 'The product is already in the cart'] , Response::HTTP_BAD_REQUEST,);
            }
        }
            else {
                return response()->json(
                    ['message' => $validator->getMessageBag()->first()],
                    Response::HTTP_BAD_REQUEST,
                );
            
            }
        }
            
   
    }

    public function MyCart(Request $request){
        if(auth::check()){
            $numOfProductsCart=Cart::where('user_id' , $request->user()->id)->count();
            $carts=Cart::with('product')->where('user_id' ,'=' ,$request->user()->id)->get();
            $total=Cart::where('user_id' ,'=' ,$request->user()->id)->sum(DB::raw('quantity * price'));
            
            return view('website.cart.my_cart',['carts'=>$carts,'total'=>$total ,'numOfProductsCart'=>$numOfProductsCart]);

        }
    }
    public function CartIncrement($rowId){

        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty +1);

        return response()->json('Increment');

    }// End Method
}
