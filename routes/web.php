<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Models\Cart;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath'
    ]
], function () {
    Route::get('/failPayment', function () { return view('website.fail');})->name('failPayment');
    Route::get('/successPayment', function () {
        return view('website.success');
    })->name('successPayment');
    Route::get('/payment', function () {
        return view('website.payment');
    })->name('payment');
    
    Route::get('forgot/password', 'Auth\ForgotPasswordController@forgotPasswordForm')->name('forgotPasswordForm');
    Route::post('sendResetLinkEmail', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('sendResetLinkEmail');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.new');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::post('coupon-apply','WEB\Site\OrderController@CouponApply')->name('applycoupon');
Route::get('/coupon-calculation','WEB\Site\OrderController@CouponCalculation' );

    Route::get('/checkPayment/{order_id}', 'API\v1\CartController@checkPayment')->name('checkPayment');
    Route::get('/mycheckout','WEB\Site\CheckoutController@showCheckout')->name('mycheckout');
    Route::post('/checkout/store','WEB\Site\OrderController@CheckoutStore')->name('storeUserCheckout');

    Route::group(['middleware' => ['auth']], function () {
        
        Route::get('/changeUserPassword', 'WEB\Site\UsersController@edit_password')->name('users.edit');
        Route::post('/updateUserPassword', 'WEB\Site\UsersController@changePassword')->name('users.edit_password');
        Route::patch('/users/account/edit/{id}', 'WEB\Site\UsersController@update')->name('users.edit_account');
        Route::get('/users/account/edit/{id}', 'WEB\Site\UsersController@edit')->name('users.edit');
        Route::get('/addresses', 'WEB\Site\AddressController@index')->name('addresses');
    Route::post('/addresses/store', 'WEB\Site\AddressController@store')->name('addresses.store');
    Route::get('/addresses/create', 'WEB\Site\AddressController@create')->name('addresses.create');
    Route::get('/adresses/{id}/edit', 'WEB\Site\AddressController@edit')->name('addresses.edit');
    Route::patch('/addresses/{id}', 'WEB\Site\AddressController@update')->name('addresses.update');
    Route::delete('addresses/{id}', 'WEB\Site\AddressController@destroy')->name('addresses.destroy');
    Route::post('/cart/data/store', 'WEB\Site\CartController@store')->name('cart.store');
    Route::get('/home', 'WEB\Site\HomeController@index')->name('home');
    Route::get('/mycart' , 'WEB\Site\CartController@MyCart')->name('mycart');
    Route::get('/myorder' , 'WEB\Site\OrderController@showOrders')->name('myorders');
    Route::get('/order/details/{id}','WEB\Site\OrderController@orderDetails')->name('orderdetails');

});

    Route::get('/sendMail', function (){
//        dispatch(function () {
            $settings = Setting::query()->first();
            $email_data = array(
                'from' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('MAIL_FROM_NAME'),
                'to' => ['mahmoud2122000ta@gmail.com']); //
//            try {
                $subject = 'شكرا لطلبك الجديد';
                $blade_data = array(
                    'subject' => $subject,
                    'settings' => $settings,
                    'order' => '$order',
                );
                    Mail::send(['html' => 'emails.order_to_user'], $blade_data, function ($message) use ($email_data, $subject) {
                        $message->to(trim('mahmoud2122000ta@gmail.com'))
                            ->subject($subject)
                            ->replyTo(trim('support@linekwdemo3.com'), trim('hello'))
                            ->from(trim('support@linekwdemo3.com'), trim('hello'));
                    });

//            } catch (Exception $e) {
//                return $e->getMessage();
//            }
//        })->afterResponse();

    });

    

    
    Route::get('/product/detail/{id}', 'WEB\Site\ProductController@ProductDetails')->name('product.details');
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', function () {
            return route('/login');
        });
        Route::get('/login', 'Auth\LoginController@showSign')->name('showSign');
        Route::get('/register', 'Auth\RegisterController@showRegister')->name('showRegister');
        Route::post('/register', 'Auth\RegisterController@store')->name('users.store');
        Route::post('/login', 'Auth\LoginController@login')->name('users.login');
    });
    
    //ADMIN AUTH ///
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', function () {
            return route('/login');
        });


        Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('admin.login.form');
        Route::post('/login', 'AdminAuth\LoginController@login')->name('admin.login');
        Route::get('/logout', 'AdminAuth\LoginController@logout')->name('admin.logout');
    });


    Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin', 'as' => 'admin.',], function () {
        Route::get('/', function () {
            return redirect('/admin/home');
        });
        Route::post('/changeStatus/{model}', 'WEB\Admin\HomeController@changeStatus');

        Route::get('home', 'WEB\Admin\HomeController@index')->name('admin.home');

        Route::get('/admins/{id}/edit', 'WEB\Admin\AdminController@edit')->name('admins.edit');
        Route::patch('/admins/{id}', 'WEB\Admin\AdminController@update')->name('users.update');
        Route::get('/admins/{id}/edit_password', 'WEB\Admin\AdminController@edit_password')->name('admins.edit_password');
        Route::post('/admins/{id}/edit_password', 'WEB\Admin\AdminController@update_password')->name('admins.edit_password');


        Route::get('/admins', 'WEB\Admin\AdminController@index')->name('admins.all');
        Route::post('/admins/changeStatus', 'WEB\Admin\AdminController@changeStatus')->name('admin_changeStatus');
        Route::delete('admins/{id}', 'WEB\Admin\AdminController@destroy')->name('admins.destroy');
        Route::post('/admins', 'WEB\Admin\AdminController@store')->name('admins.store');
        Route::get('/admins/create', 'WEB\Admin\AdminController@create')->name('admins.create');
        Route::get('/editMyProfile', 'WEB\Admin\AdminController@editMyProfile')->name('admins.editMyProfile');
        Route::post('/updateProfile', 'WEB\Admin\AdminController@updateProfile')->name('admins.updateProfile');
        Route::get('/changeMyPassword', 'WEB\Admin\AdminController@changeMyPassword')->name('admins.changeMyPassword');
        Route::post('/updateMyPassword', 'WEB\Admin\AdminController@updateMyPassword')->name('admins.updateMyPassword');



        //Route::get('/users', 'WEB\Admin\UsersController@index')->name('users.all');
       // Route::post('/users', 'WEB\Admin\UsersController@store')->name('users.store');
        //Route::get('/users/create', 'WEB\Admin\UsersController@create')->name('users.create');
     //   Route::delete('users/{id}', 'WEB\Admin\UsersController@destroy')->name('users.destroy');
      //  Route::get('/users/{id}/edit', 'WEB\Admin\UsersController@edit')->name('users.edit');
      //  Route::get('/users/{id}/show', 'WEB\Admin\UsersController@show')->name('users.show');
      //  Route::get('/users/{id}/orders', 'WEB\Admin\UsersController@orders')->name('users.orders');
      //  Route::get('/users/{id}/{order_id}/editOrder', 'WEB\Admin\UsersController@editOrder')->name('users.editOrder');
      //  Route::patch('/users/{id}', 'WEB\Admin\UsersController@update')->name('users.update');
      //  Route::get('/users/{id}/edit_password', 'WEB\Admin\UsersController@edit_password')->name('users.edit_password');
       // Route::post('/users/{id}/edit_password', 'WEB\Admin\UsersController@update_password')->name('users.edit_password');
       // Route::get('/exportUsers', 'WEB\Admin\UsersController@exportUsers');
       // Route::get('/pdfUsers', 'WEB\Admin\UsersController@pdfUsers');



        
        
        Route::resource('/categories', 'WEB\Admin\CategoryController');



        Route::get('/report/meals', 'WEB\Admin\MealController@report')->name('mealsReport');
        Route::get('/export/excel/meals', 'WEB\Admin\MealController@exportExcel');
        Route::get('/MealsReportForAdmin/excel/meals', 'WEB\Admin\MealController@MealsReportForAdmin');
        Route::resource('/meals', 'WEB\Admin\MealController');
        Route::get('/meals/{id}/options', 'WEB\Admin\MealController@options');
        Route::get('/meals/{id}/createOption', 'WEB\Admin\MealController@createOption');
        Route::post('/meals/{id}/storeOption', 'WEB\Admin\MealController@storeOption');
        Route::get('/meals/{id}/editOption', 'WEB\Admin\MealController@editOption');
        Route::post('/meals/{id}/updateOption', 'WEB\Admin\MealController@updateOption');
        Route::delete('/meals/{id}/deleteOption', 'WEB\Admin\MealController@deleteOption');
        Route::delete('/meals/{id}/deleteOffer', 'WEB\Admin\MealController@deleteOffer');


        Route::resource('/cities', 'WEB\Admin\CityController');
        Route::resource('/promo_codes', 'WEB\Admin\PromoCodesController');


        Route::get('getNewOrdersCount/orders','WEB\Admin\OrderController@getNewOrdersCount');
       // Route::get('invoice/orders/{id}','WEB\Admin\OrderController@invoice')->name('invoice');
      //  Route::get('refund/orders/{id}','WEB\Admin\OrderController@refund');
        Route::get('changeOrdersCount/orders','WEB\Admin\OrderController@changeOrdersCount');
        Route::get('/orders/changeStatus/{id}/{status}', 'WEB\Admin\OrderController@changeStatus')->name('changeOrderStatus');
       // Route::get('/report/orders', 'WEB\Admin\OrderController@report')->name('ordersReport');
        Route::get('/pdfOrders', 'WEB\Admin\OrderController@pdfOrders');
        Route::get('/export/excel/orders', 'WEB\Admin\OrderController@OrdersExportForAdmin');
        
        Route::resource('/orders', 'WEB\Admin\OrderController');
        Route::resource('/notifications', 'WEB\Admin\NotificationsController');

        Route::resource('/sizes', 'WEB\Admin\SizeController');
        Route::resource('/brands', 'WEB\Admin\BrandController');
        Route::resource('/colors', 'WEB\Admin\ColorController');
        Route::resource('/countries', 'WEB\Admin\CountryController');
        Route::resource('/products', 'WEB\Admin\ProductController');

        


       


        Route::get('settings', 'WEB\Admin\SettingController@index')->name('settings.all');
        Route::post('settings', 'WEB\Admin\SettingController@update')->name('settings.update');

        Route::get('system_maintenance', 'WEB\Admin\SettingController@system_maintenance')->name('system.maintenance');
        Route::post('system_maintenance', 'WEB\Admin\SettingController@update_system_maintenance')->name('update.system.maintenance');


        Route::resource('/pages', 'WEB\Admin\PagesController');

        Route::resource('/roles', 'WEB\Admin\RolesController');
        Route::resource('/notifications', 'WEB\Admin\NotificationsController');

        Route::get('logs', 'WEB\Admin\LogController@index');


    });

});

