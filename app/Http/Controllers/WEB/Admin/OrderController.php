<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Exports\OrdersExportForAdmin;
use App\Exports\OrdersReportForAdmin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Subadmin;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->locales = Language::all();
        $this->settings = Setting::query()->first();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);


        $route = Route::currentRouteAction();
        $route_name = substr($route, strpos($route, "@") + 1);
        $this->middleware(function ($request, $next) use ($route_name) {

            if ($route_name == 'index') {
                if (can(['orders-show', 'orders-create', 'orders-edit', 'orders-delete'])) {
                    return $next($request);
                }
            } elseif ($route_name == 'create' || $route_name == 'store') {
                if (can('orders-create')) {
                    return $next($request);
                }
            } elseif ($route_name == 'edit' || $route_name == 'update') {
                if (can('orders-edit')) {
                    return $next($request);
                }
            } elseif ($route_name == 'destroy' || $route_name == 'delete') {
                if (can('orders-delete')) {
                    return $next($request);
                }
            } else {
                return $next($request);
            }
            return redirect()->back()->withErrors(__('cp.you_dont_have_permission'));
        });


    }

    public function index()
    {
        
        $items = Order::filter()->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        return view('admin.orders.home', [
            'items' => $items,
            
        ]);
    }

    public function report()
    {

        
        $items = Order::filter()->where('status', '3')->orderBy('id', 'desc')->paginate($this->settings->paginateTotal);
        return view('admin.orders.report', [
            'items' => $items,
           
        ]);
    }


    public function create()
    {
        $users = User::get();
       
        return view('admin.orders.create')->with(compact('users'));
    }

    public function edit($id)
    {
        $item = Order::where('id', $id)->first();
        return view('admin.orders.show', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $roles = [

        ];
        $item = Order::query()->where('id', $id)->first();
        if ($item->status != '3' && $item->status != '4') {
            $roles = [
                'status' => 'required',
            ];
        }
        $this->validate($request, $roles);

        if ($item->status == '3' || $item->status == '4') {
            return redirect()->back()->with('status', __('cp.update'));
        }
        if ($item->status == 1) {
            return redirect()->back()->with('status', __('cp.update'));
        } else {

            $message_en = '';
            $message_ar = '';
            if ($item->status == 2) {
                $message_en = 'You order is Being Prepared';
                $message_ar = '???????? ?????? ??????????????';
            } elseif ($item->status == 3) {
                $message_en = 'Your order is Ready for Pick Up';
                $message_ar = '???????? ???????? ????????????????';
            } elseif ($item->status == 4) {
                $message_en = 'Your order has been picked up';
                $message_ar = '???? ?????????? ????????';
            } elseif ($item->status == 5) {
                $message_en = 'Sorry! Your order has been cancelled, please contact our customer service.';
                $message_ar = '???????? ! ???? ?????????? ???????? , ???????? ?????????????? ???? ???????? ??????????????';
            }
            $locales = Language::all()->pluck('lang');
            $usersIDs = User::query()->where('notifications', '1')->where('id', $item->user_id)->pluck('id')->toArray();
            $notify = new Notification();

            $notify->translateOrNew('en')->title = 'Order #' . $item->id;
            $notify->translateOrNew('ar')->title = $item->id . '?????? #';
            $notify->translateOrNew('en')->message = $message_en;
            $notify->translateOrNew('ar')->message = $message_ar;
            $notify->target_id = $item->id;
            $notify->user_id = $item->user_id;
            $notify->fcm_token = $item->fcm_token;
            $notify->type = '2';
            $notify->save();

            $tokens_en = Token::where('lang', 'en')->whereIn('user_id', $usersIDs)->orWhere('fcm_token', $item->fcm_token)->pluck('fcm_token')->toArray();
            $tokens_ar = Token::where('lang', 'ar')->whereIn('user_id', $usersIDs)->orWhere('fcm_token', $item->fcm_token)->pluck('fcm_token')->toArray();
            sendNotificationToUsers($tokens_en, '2', $item->id, 'Order #' . $item->id, $message_en);
            sendNotificationToUsers($tokens_ar, '2', $item->id, $item->id . '?????? #', $message_ar);
        }
        activity($item->id)->causedBy(auth('admin')->user())->log('?????????? ???? ???????? ?????????? ');


        return redirect()->back()->with('status', __('cp.update'));


        

       
        
      

       
    }

    public function changeStatus($id, $status)
    {
        $item = Order::where('id', $id)->first();
        if ($item->status != 4 && $item->status != 5) {
            $item->status = $status;
            $item->save();
            return redirect()->back();
            
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        //
        $ad = Order::query()->findOrFail($id);
        if ($ad) {
            Order::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

    public function OrdersExportForAdmin(Request $request)
    {
        activity()->causedBy(auth('admin')->user())->log(' ?????????? ?????? ???????? ?????????????? ?????????????? ');
        return Excel::download(new OrdersExportForAdmin($request), 'orders.xlsx');
    }

    
    public function getNewOrdersCount()
    {
        if (Session::get('admin_total_notifications')) {
            return response()->json(Session::get('admin_total_notifications'));
        } else {
            return response()->json(0);
        }
    }
    public function changeOrdersCount(Request $request)
    {
        $value = $request->value;
        Session::put('admin_total_notifications', $value);
        response()->json('success');
    }

    public function pdfOrders(Request $request)
    {
        activity()->causedBy(auth('admin')->user())->log(' ?????????? ?????? PDF ?????????????? ?????????????? ');
        $items = Order::orderByDesc('id')->get();
        $pdf = PDF::loadView('admin.orders.export_pdf', ['items' => $items]);
        return $pdf->download('orders.pdf');
    }
    


}
