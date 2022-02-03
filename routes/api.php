<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    ProvinceController,
    LoginController,
    RegisterController,
    VehicleController,
    SignController,
    UserController,
    LoginSellerController,
    SellerController,
    RequestUserController,
    UserCartController,
    UserOrderController,
    LoginShipperController,
    ShipperController
};

use App\Http\Controllers\AppVersionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('versioning',AppVersionController::class);


Route::apiResource('provinces',ProvinceController::class);

Route::middleware(['throttle:login'])->group(function (){

    // Auth and register for user
    Route::prefix('user')->group(function(){
        Route::post('login',LoginController::class);
        Route::post('register',RegisterController::class);
    });

// auth for seller

    Route::prefix('seller')->group(function(){
        Route::post('login',LoginSellerController::class);
    });

// auth for shipper

    Route::prefix('shipper')->group(function (){
        Route::post('login',LoginShipperController::class);
    });

});

Route::middleware('auth:sanctum')->group(function(){

    Route::prefix('/user')->group(function(){
        Route::get('insert_location',[UserController::class,'insert_location']);
        Route::get('list_suggestions_request/{request_id}',[UserController::class,'list_suggestions_request'])->whereNumber('request_id');
        Route::get('user_list_requests_by_vehicle/{user_vehicle_id}',[UserController::class,'user_list_requests_by_vehicle'])->whereNumber('user_vehicle_id');
        Route::get('count_suggestions_request/{request_id}',[UserController::class,'count_suggestions_request'])->whereNumber('request_id');

        // cart
        Route::prefix('cart')->group(function(){
            Route::get('index',[UserCartController::class,'info_cart']);
            Route::post('store',[UserCartController::class,'store_cart']);
            Route::delete('destory_item/{id}',[UserCartController::class,'destory_items_cart'])->whereNumber('id');
            Route::delete('destory/{id}',[UserCartController::class,'destory_cart'])->whereNumber('id');
        });

        // order
        Route::prefix('order')->group(function(){
            Route::get('check_order/{user_order_id}',[UserController::class,'check_user_order'])->whereNumber('user_order_id');
            Route::get('index',[UserOrderController::class,'list_orders']);
            Route::get('detail/{id}',[UserOrderController::class,'order_details'])->whereNumber('id');
            Route::post('store',[UserOrderController::class,'store_order']);
        });

    });

    Route::prefix('vehicle')->group(function (){
        Route::post('store_control',[VehicleController::class,'store_control']);
        Route::put('update_control/{user_vehicle_id}',[VehicleController::class,'update_control'])->whereNumber('user_vehicle_id');
    });

    Route::prefix('/seller')->group(function(){
        Route::get('store_device_token',[SellerController::class,'store_device_token']);
        Route::get('insert_location',[SellerController::class,'insert_location']);
        Route::get('list_requests',[SellerController::class,'list_requests']);
        Route::get('count_seller_requests_by_type/{types}',[SellerController::class,'count_seller_requests_by_type']);
        Route::get('to_cash',[SellerController::class,'to_cash']);
        Route::get('cash',[SellerController::class,'cash']);
        Route::post('store_seller_suggestion',[SellerController::class,'store_seller_suggestion']);
    });

    Route::prefix('/shipper')->group(function (){
        Route::get('store_device_token',[ShipperController::class,'store_device_token']);
        Route::get('order_requests',[ShipperController::class,'index']);
        Route::get('count_order_by_type/{types}',[ShipperController::class,'count_orders_by_delivery_type']);
        Route::put('confirm_order/{order_user_id}',[ShipperController::class,'confirm_order'])->whereNumber('order_user_id');
        Route::get('recovery_orders',[ShipperController::class,'get_recovery_orders']);
        Route::get('confirm_recover_order/{order_user_id}/{coord}',[ShipperController::class,'recover_order'])->whereNumber('order_user_id');
        Route::get('delivery_orders',[ShipperController::class,'get_delivery_orders']);
        Route::get('confirm_delivery_order/{order_user_id}/{coord}',[ShipperController::class,'delivery_order'])->whereNumber('order_user_id');
        Route::get('commissions',[ShipperController::class,'shipper_commissions']);
    });

    Route::apiResources([
        'sign' => SignController::class,
        'vehicle' => VehicleController::class,
        'user/request' => RequestUserController::class,
    ]);
});
