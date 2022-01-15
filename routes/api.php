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
        Route::get('insert_location/{location?}',[UserController::class,'insert_location'])->whereAlphaNumeric('location');
    });

    Route::prefix('/seller')->group(function(){
        Route::get('insert_locationn/{location?}',[SellerController::class,'insert_location']);
    });

    Route::apiResources([
        'sign' => SignController::class,
        'vehicle' => VehicleController::class,
        'user/request' => RequestUserController::class,
    ]);
});
