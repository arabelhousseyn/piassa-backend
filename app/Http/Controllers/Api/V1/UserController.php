<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{UserRequest};
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function insert_location($location)
    {
        Auth::user()->locations()->create([
            'location' => $location
        ]);
        return response(['success' => true],200);
    }

    public function list_suggestions_request($request_id)
    {
        $data = UserRequest::with('suggestions.suggestion')
            ->with(['suggestions' => function($query){
                return $query->whereNotNull('suggest_him_at');
            }])->whereId($request_id)->first();

        $suggestions = $data->suggestions->map(function($map){
            return $map->only('suggestion');
        });
        return response($suggestions,200);
    }

    public function user_list_requests_by_vehicle($user_vehicle_id)
    {
        $requests = UserRequest::with('informations')->where('user_vehicle_id',$user_vehicle_id)->get();
        return response($requests,200);
    }
}
