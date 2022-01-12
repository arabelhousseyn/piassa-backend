<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    ProvinceController,
    LoginController,
    RegisterController,
    VehicleController,
    SignController,
    UserController
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

Route::post('login',LoginController::class);
Route::post('register',RegisterController::class);

Route::middleware('auth:sanctum')->group(function(){

    Route::prefix('/user')->group(function(){
        Route::get('insert_location/{location?}',[UserController::class,'insert_location'])->whereAlphaNumeric('location');
    });

    Route::apiResource('sign',SignController::class);
    Route::apiResource('vehicle',VehicleController::class);
});
