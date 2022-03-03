<?php

namespace App\Http\Middleware;

use App\Models\Sign;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckChassisNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $sign = Sign::find($request->sign_id);
        if($sign->prefix !== null)
        {
            if(!Str::startsWith($request->chassis_number,$sign->prefix))
            {
                $message = [
                    'message' => [
                        'errors' => [
                            'chassis_number' => [
                                __('message.chassis_error')
                            ]
                        ]
                    ]
                ];
                return response($message,302);
            }
        }

        return $next($request);
    }
}
