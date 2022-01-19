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
        $filter = [];
        $data = UserRequest::with(['suggestions.suggestion' => function($query){
            return $query->whereNull('taken_at');
        }])
            ->with(['suggestions' => function($query){
                return $query->whereNotNull('suggest_him_at');
            }])->whereId($request_id)->first();

        foreach ($data->suggestions as $value)
        {
            if($value->suggestion->available_at)
            {
                $filter[] = $value->suggestion;
            }
        }

        return response($filter,200);
    }

    public function user_list_requests_by_vehicle($user_vehicle_id)
    {
        $requests = UserRequest::with('informations')->where('user_vehicle_id',$user_vehicle_id)->get();
        return response($requests,200);
    }

    public function count_suggestions_request($request_id)
    {
        $suggestions = UserRequest::with('suggestions','vehicle.user')->find($request_id);

        return response(['count' => count($suggestions->suggestions)],200);
    }
}
