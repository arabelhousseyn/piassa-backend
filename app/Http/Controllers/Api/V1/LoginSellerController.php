<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginSellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;
class LoginSellerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginSellerRequest $request)
    {
        if($request->validated())
        {
            if(Auth::guard('seller')->attempt($request->only('phone','password')))
            {
                $user = Auth::guard('seller')->user();
                $token = $user->createToken('piassa')->plainTextToken;
                $seller = Seller::with('profile','jobs')->find(Auth::guard('seller')->id());
                $seller['token'] = $token;
                return response($seller,200);
            }else{
                $message = [
                    'message' => [
                        'errors' => [
                            'Un de vos informations incorrect'
                        ]
                    ]
                ];
                return response($message,403);
            }
        }
    }
}
