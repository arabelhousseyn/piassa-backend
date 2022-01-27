<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,UserProfile,UserLocation};

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        if($request->validated())
        {
            $user = User::create([
                'phone' => $request->phone,
                'password' => Hash::make($request->password_confirmation),
            ]);

            $user->profile()->create([
                'province_id' => $request->province_id,
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'device_token' => (strlen($request->device_token) == 0) ? null : $request->device_token
            ]);

            $user->locations()->create([
                'location' => $request->location
            ]);

            $token = $user->createToken('piassa')->plainTextToken;

            switch ($request->has('has_role'))
            {
                case 'P' :
                    $user->assignRole('P');
                    break;
                case 'C' :
                    $user->assignRole('C');
                    break;
                case 'A' :
                    $user->assignRole('D');
                    break;
            }

            return response(['success' => true,'token' => $token],200);
        }
    }
}
