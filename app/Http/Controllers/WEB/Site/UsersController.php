<?php

namespace App\Http\Controllers\WEB\Site;


use App\Exports\UsersExport;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Token;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use Illuminate\Auth\Notifications\ResetPassword as NotificationsResetPassword;

use Image;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class UsersController extends Controller
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
            if(can('users')){
                return $next($request);
            }
            if($route_name== 'index' ){
                if(can(['users-show' , 'users-create' ,  'users-edit' , 'users-delete'])){
                    return $next($request);
                }
            }elseif($route_name== 'edit' || $route_name== 'update'){
                if(can('users-edit')){
                    return $next($request);
                }
            }elseif($route_name== 'destroy' || $route_name== 'delete'){
                if(can('users-delete')){
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
        $items = User::query()->filter()->orderBy('id', 'desc')->where(['is_deleted'=>'0'])->paginate($this->settings->paginateTotal);
        return view('admin.users.home', [
            'items' => $items,
        ]);


    }
    

    public function orders($id){
        $item = User::query()->where('id',$id)->first();
        $items = Order::where('user_id',$id)->orderByDesc('id')->paginate(30);
        return view('admin.users.orders')->with(compact('items','item'));
    }


    public function editOrder($id,$order_id){
        $item = User::query()->where('id',$id)->first();
        $order = Order::where('id',$order_id)->first();
        return view('admin.users.edit_order')->with(compact('order','item'));
    }

    
    
    
    public function edit()
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        return view('website.edit_account', [
            'user' => $user,
        ]);
    }


    public function update(Request $request, $id)
    {
        $roles =[
            'user_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|digits_between:8,12|unique:users,mobile,' . $id,
           
        ];
        $this->validate($request, $roles);
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json(['message'=>'edit account success' ]);
    }


    public function edit_password()
    {
        $user= User::findOrFail(auth()->user()->id);
        return view('website.password', ['user' => $user]);
    }
   


    public function changePassword(Request $request)
    {
        
        $request->validate([
            'password'=>'required',
             'new_password' => 'required|confirmed',
             
        ]);

        if(!Hash::check($request->password,auth::user()->password))
         {
            return back()->with('error','Old password does not match');
         }

         
         User::whereId(auth()->user()->id)->update([
           'new_password' => Hash::make($request->new_password)
         ]);

         return response()->json(['message'=>' change_password success' ]);
    }

    public function destroy($id)
    {
        $item = User::query()->findOrFail($id);
        if ($item) {
            User::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }


    public function exportUsers(Request $request)
    {
        activity()->causedBy(auth('admin')->user())->log(' تصدير ملف إكسل لبيانات المستخدمين ');
        return Excel::download(new UsersExport($request), 'users.xlsx');
    }

    public function pdfUsers(Request $request)
    {
        activity()->causedBy(auth('admin')->user())->log(' تصدير ملف PDF لبيانات المستخدمين ');
        $items = User::orderByDesc('id')->get();
        $pdf = PDF::loadView('admin.users.export_pdf', ['items'=>$items]);
        return $pdf->download('users.pdf');
    }
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();

        return redirect('/user/sign');
    }

}
