<?php

use Illuminate\Http\Request;
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
    RequestUserController
};
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('provinces',ProvinceController::class);

Route::prefix('user')->group(function(){
    Route::post('login',LoginController::class);
    Route::post('register',RegisterController::class);
});

Route::prefix('seller')->group(function(){
    Route::post('login',LoginSellerController::class);
});

Route::middleware('auth:sanctum')->group(function(){

    Route::prefix('/user')->group(function(){
        Route::get('insert_location/{location?}',[UserController::class,'insert_location']);
        Route::get('list_suggestions_request/{request_id}',[UserController::class,'list_suggestions_request'])->whereNumber('request_id');
        Route::get('user_list_requests_by_vehicle/{user_vehicle_id}',[UserController::class,'user_list_requests_by_vehicle'])->whereNumber('user_vehicle_id');
        Route::get('count_suggestions_request/{request_id}',[UserController::class,'count_suggestions_request'])->whereNumber('request_id');
    });

    Route::prefix('/seller')->group(function(){
        Route::get('insert_locationn/{location?}',[SellerController::class,'insert_location']);
        Route::get('list_requests',[SellerController::class,'list_requests']);
        Route::get('count_seller_requests_by_type/{types}',[SellerController::class,'count_seller_requests_by_type']);
        Route::post('store_seller_suggestion',[SellerController::class,'store_seller_suggestion']);
    });

    Route::apiResources([
        'sign' => SignController::class,
        'vehicle' => VehicleController::class,
        'user/request' => RequestUserController::class,
    ]);
});
