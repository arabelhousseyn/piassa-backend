<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        if($request->validated())
        {
            if(Auth::attempt($request->only('phone','password')))
            {
                $user = User::with('profile')->find(Auth::id());
                $token = $user->createToken('piassa')->plainTextToken;
                $user['token'] = $token;
                return response($user,200);
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
