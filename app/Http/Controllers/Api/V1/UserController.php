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
        $final = [];
        $data = UserRequest::with(['suggestions' => function($query){
                return $query->whereNotNull('suggest_him_at');
            }])->with(['suggestions.suggestion' => function($query){
            return $query->whereNull('taken_at');
        }])->whereId($request_id)->first();

        foreach ($data->suggestions as $value) {
            if($value->suggest_him_at !== null)
            {
                $final[] = $value;
            }
        }

        return response($final,200);
    }

    public function user_list_requests_by_vehicle($user_vehicle_id)
    {
        $requests = UserRequest::with('informations')->where('user_vehicle_id',$user_vehicle_id)->get();
        return response($requests,200);
    }

    public function count_suggestions_request($request_id)
    {
        $suggestions = UserRequest::with('suggestions')->find($request_id);

        return response(['count' => count($suggestions->suggestions)],200);
    }
}
