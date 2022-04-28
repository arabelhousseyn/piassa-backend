<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, UserRequest, UserOrder};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Rules\FilterLocation;
class UserController extends Controller
{
    public function insert_location(Request $request)
    {
        $rules = [
            'location' => ['required', new FilterLocation]
        ];

        $validator = Validator::make($request->only('location'),$rules);

        if($validator->fails())
        {
            $message = [
                'message' => [
                    'errors' => [
                        $validator->errors()
                    ]
                ]
            ];
            return response($message,403);
        }

        if($validator->validated())
        {
            Auth::user()->locations()->create([
                'location' => $request->location
            ]);
            return response(['success' => true],200);
        }
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
                foreach ($value->suggestion as $value)
                {
                    $final[] = $value;
                }
            }
        }

        return response($final,200);
    }

    public function list_suggestions()
    {
        $final = [];
        $data = User::with(['vehicle.requests.suggestions' => function($query){
            return $query->whereNotNull('suggest_him_at');
        }])->with(['vehicle.requests.suggestions.suggestion' => function($query){
            return $query->whereNull('taken_at');
        }])->with('vehicle.requests.informations')
            ->with('vehicle.requests.type')
            ->with('vehicle.requests.images')->find(Auth::id());

        foreach ($data->vehicle as $item)
        {
            foreach ($item->requests as $request)
            {
                foreach ($request->suggestions as $value) {
                    if($value->suggest_him_at !== null)
                    {
                        foreach ($value->suggestion as $value)
                        {
                            $final[] = [
                                'suggestion' => $value,
                                'informations' => $request->informations,
                                'type' => $request->type,
                                'images' => $request->images
                            ];
                        }
                    }
                }
            }
        }

        return response($final,200);
    }

    public function user_list_requests_by_vehicle($user_vehicle_id)
    {
        $requests = UserRequest::with('informations','type:id,name')->where('user_vehicle_id',$user_vehicle_id)->get();
        return response($requests,200);
    }

    public function count_suggestions_request($request_id)
    {
        $count = 0;
        $suggestions = UserRequest::with('suggestions')->find($request_id);

        foreach ($suggestions->suggestions as $suggestion) {
            if($suggestion->suggest_him_at !== null)
            {
                $count++;
            }
        }

        return response(['count' => $count],200);
    }

    public function check_user_order($user_order_id)
    {
        $check = UserOrder::find($user_order_id);
        if($check)
        {
            return response(['success' => true],200);
        }else{
            $message = [
                'message' => [
                    'errors' => [
                        'Commande non valide'
                    ]
                ]
            ];
            return response($message,403);
        }
    }
}
