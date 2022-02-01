<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginShipperRequest;
use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginShipperController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginShipperRequest $request)
    {
        if($request->validated())
        {
            if(Auth::guard('shipper')->attempt($request->only('phone','password')))
            {
                $user = Auth::guard('shipper')->user();
                $token = $user->createToken('piassa')->plainTextToken;
                $shipper = Shipper::with('profile')->find(Auth::guard('shipper')->id());
                $shipper['token'] = $token;
                return response($shipper,200);
            }else{
                $message = [
                    'message' => [
                        'errors' => [
                            __('message.incorrect')
                        ]
                    ]
                ];
                return response($message,302);
            }
        }
    }
}
