<?php

namespace App\Services;

use App\Events\NewRequestEvent;
use App\Models\{UserVehicle,UserRequest,User,Seller};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class RequestUserService{

    public function store($request)
    {
            $user = User::find(Auth::id());
            $user_vehicle = UserVehicle::find($request->user_vehicle_id);
            if(!$user->can('handle_vehicle',$user_vehicle))
            {
                $message = [
                    'message' => [
                        'error' => [
                            __('message.vehilce_error')
                        ]
                    ]
                ];

                return response($message,403);
            }

        $data = $request->except('type','user_vehicle_id');

        $operation = UserRequest::create($request->only('user_vehicle_id','qt','type_id'));

        foreach ($data as $key => $value) {
            $operation->informations()->create([
                'attribute' => $key,
                'value' => $value,
                'attributeable_type' => get_class($operation),
                'attributeable_id' => $operation->id
            ]);
        }
        $distances = [];
        $user = User::with(['locations' => function($query){
            return $query->orderBy('id','desc')->first();
        }])->find(Auth::id());

        $sellers = Seller::with('profile','jobs')->get();
        foreach ($sellers as $seller)
        {
            $open = false;
            if($seller->profile->location !== null)
            {
                foreach ($seller->jobs as $job)
                {
                    if($job->type_id == Str::upper($operation->type_id))
                    {
                        $open = true;
                    }
                }
                if($open)
                {
                    $info1 = explode(',',$seller->profile->location);
                    $info2 = explode(',',$user->locations[0]->location);
                    $distance = GeoFacade::setPoint([doubleval($info1[0]), doubleval($info1[1])])
                        ->setOptions(['units' => ['km']])
                        ->setPoint([doubleval($info2[0]), doubleval($info2[1])])
                        ->getDistance();

                    if(array_key_exists('2-3',$distance))
                    {
                        $arr = [
                            'seller_id' => $seller->id,
                            'distance' => $distance['2-3']['km']
                        ];
                        $distances[] = $arr;
                    }elseif(array_key_exists('1-2',$distance)){

                        $arr = [
                            'seller_id' => $seller->id,
                            'distance' => $distance['1-2']['km']
                        ];
                        $distances[] = $arr;
                    }
                }
            }
        }
        $temp = [];
        foreach ($distances as $distance)
        {
            $temp[] = $distance['distance'];
        }
        sort($temp);

        foreach ($distances as $distance)
        {
            if(in_array($distance['distance'],$temp))
            {
                $seller = Seller::find($distance['seller_id']);
                $seller->requests()->create([
                    'user_request_id' => $operation->id,
                ]);
            }
        }

        event(New NewRequestEvent($operation));

        return response(['success' => true,'request_id' => $operation->id],201);
    }

}
