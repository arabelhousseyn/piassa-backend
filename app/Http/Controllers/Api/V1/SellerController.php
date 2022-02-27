<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreSellerSuggestionRequest;
use App\Rules\FilterLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{Seller, SellerRequest};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Events\{NewSuggestionEvent,NewRequestEvent};
class SellerController extends Controller
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
            Auth::user()->profile()->update([
                'location' => $request->location
            ]);
            return response(['success' => true],200);
        }
    }

    public function list_requests()
    {
        $data = [];
        $seller = Seller::with('requests.suggestion','requests.request.vehicle.sign','requests.request.vehicle.user.profile.province','requests.request.informations'
        ,'requests.request.vehicle.user.locations')
            ->with(['requests' => function($query){
                return $query->whereNull('suggest_him_at');
            }])
            ->with(['requests.request' => function ($query){
                return $query->whereNull('expired_at');
            }])
            ->find(Auth::id());

        $subset = $seller->requests->map(function ($collection){
            return $collection->only('request');
        });

        foreach ($subset as $item) {
            if(@$item['request']->id)
            {
                $data[] = $item;
            }
        }

        return response($data,200);
    }

    public function count_seller_requests_by_type($types)
    {
        $seller = Seller::with('requests.request.type')->find(Auth::id());
        $data = [];
        $extract = explode(',',$types);
        foreach ($extract as $type)
        {
            $count = 0;
            foreach ($seller->requests as $request)
            {
                if($request->request->type_id == Str::upper($type))
                {
                    $count++;
                }
            }
            $data[] = [
                'type' => $request->request->type->only('id','name'),
                'count' => $count
            ];
        }

        return response($data,200);

    }


    public function store_seller_suggestion(StoreSellerSuggestionRequest $request)
    {
        $marks = explode(',',$request->marks);
        $prices = explode(',',$request->prices);
        $available_at = explode(',',$request->available_at);

        if(count($marks) == count($prices) && count($marks) == count($available_at) &&
        count($prices) == count($available_at))
            {
                $seller_request = SellerRequest::find($request->seller_request_id);
                if(!Auth::user()->can('handle_request',$seller_request))
                {
                    $message = [
                        'message' => [
                            'error' => [
                                __('message.request_error')
                            ]
                        ]
                    ];

                    return response($message,403);
                }

                SellerRequest::whereId($request->seller_request_id)->update([
                    'suggest_him_at' => Carbon::now()
                ]);

                for ($i=0;$i<count($marks);$i++)
                {
                    $seller_request->suggestion()->create([
                        'mark' => $marks[$i],
                        'price' => $prices[$i],
                        'available_at' => $available_at[$i],
                    ]);
                }
                $data = SellerRequest::with('suggestion')->find($request->seller_request_id);
                event(new NewSuggestionEvent($data));
                event(new NewRequestEvent($data));
                return response(['success' => true],201);

            }else{
            $message = [
                'message' => [
                    'errors' => [
                        'Erreur veuillez réessayer.'
                    ]
                ]
            ];
            return response($message,403);
        }
    }

    public function to_cash()
    {
        $final  = [];
        $seller = Seller::with(['requests'=> function($query){
            return $query->whereNotNull('suggest_him_at');
        }])->with(['requests.suggestion' => function($query){
            return $query->whereNotNull('delivred_at');
        }])->with('requests.suggestion.ordred.order.shipperUserOrder.order',
            'requests.suggestion.ordred.order.items.item.request.request.informations')
            ->with('requests.suggestion.ordred.order.events')->find(Auth::id());

        foreach ($seller->requests as $value) {
            if(@$value->suggestion->ordred->order->id)
            {
                if(count($value->suggestion->ordred->order->events) == 1)
                {
                    $final[] = $value;
                }
            }
        }
        return response($final,200);
    }

    public function cash()
    {
        $final  = [];
        $seller = Seller::with(['requests'=> function($query){
            return $query->whereNotNull('suggest_him_at');
        }])->with(['requests.suggestion' => function($query){
            return $query->whereNotNull('delivred_at');
        }])->with('requests.suggestion.ordred.order.shipperUserOrder.order',
        'requests.suggestion.ordred.order.items.item.request.request.informations')
            ->with('requests.suggestion.ordred.order.events')->find(Auth::id());

        foreach ($seller->requests as $value) {
            if(@$value->suggestion->ordred->order->id)
            {
                if(count($value->suggestion->ordred->order->events) == 2)
                {
                    $final[] = $value;
                }
            }
        }
        return response($final,200);
    }

    public function store_device_token(Request $request)
    {
        $rules = [
            'device_token' => 'required'
        ];
        $validator = Validator::make($request->only('device_token'),$rules);
        if($validator->fails())
        {
            $message = [
                'message' => [
                    'errors' => [
                        'Erreur veuillez réessayer.'
                    ]
                ]
            ];
            return response($message,403);
        }

        if($validator->validated())
        {
            Auth::user()->profile()->update([
                'device_token' => $request->device_token
            ]);

            return response(['success' => true],200);
        }
    }

    public function store_phone(Request $request)
    {
        $rules = [
            'phone' => 'required|digits:10|unique:sellers,phone|unique:seller_phones,phone',
            'name' => 'required'
        ];

        $validated = $request->validate($rules);

        Auth::user()->phones()->create([
            'phone' => $validated['phone'],
            'name' => $validated['name']
        ]);

        return response(['success' => true],201);
    }

    public function seller_phone()
    {
        $phones = Auth::user()->phones;

        $subset = $phones->map(function ($filter){
            return $filter->only('phone','name');
        });

        return response($subset,200);
    }
}
