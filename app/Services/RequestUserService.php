<?php

namespace App\Services;

use App\Events\NewRequestForSellerEvent;
use Illuminate\Support\Facades\Log;
use App\Models\{UserVehicle,UserRequest,User,Seller};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;
use App\Traits\{UploadTrait,CustomPushNotificationTrait};
class RequestUserService{
    use UploadTrait, CustomPushNotificationTrait;
    public function store($request)
    {
            $user = User::find(Auth::id());
            $user_vehicle = UserVehicle::with('user.profile')->find($request->user_vehicle_id);
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

        $data = $request->except('type_id','user_vehicle_id','images');

        $operation = UserRequest::create($request->only('user_vehicle_id','type_id'));

        if($request->has('images'))
        {
            $images = explode(';',$request->images);
            foreach ($images as $image) {
                $path = $this->uploadImageAsBase64($image,'requestImages');
                $operation->images()->create(['path'=>$path]);
            }
        }


        foreach ($data as $key => $value) {
            $operation->informations()->create([
                'value' => json_encode($value),
                'attributeable_type' => get_class($operation),
                'attributeable_id' => $operation->id
            ]);
        }

//        foreach ($data as $key => $value) {
//            $values = explode(',',$value);
//            for ($i=0;$i<count($values);$i++)
//            {
//                $operation->informations()->create([
//                    'attribute' => $key,
//                    'value' => $values[$i],
//                    'attributeable_type' => get_class($operation),
//                    'attributeable_id' => $operation->id
//                ]);
//            }
//        }
        $distances = [];
        $user = User::with(['locations' => function($query){
            return $query->orderBy('id','desc')->first();
        }])->find(Auth::id());

        $sellers = Seller::with('profile','signs','types')->get();
        $seller_ids = array();
        foreach ($sellers as $seller)
        {
            $signs = [];
            $types = [];
            $open = false;
            if($seller->profile->location !== null)
            {
                foreach ($seller->signs as $sign) {
                    $signs[] = $sign->sign_id;
                }

                foreach ($seller->types as $type) {
                    $types[] = $type->type_id;
                }
                if(in_array($operation->type_id,$types) && in_array($user_vehicle->sign_id,$signs))
                {
                    $open = true;
                }

                if($open)
                {
                    $seller_ids[] = $seller->id;
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
                    $data = Seller::with('requests.suggestion','requests.request.vehicle.sign','requests.request.vehicle.user.profile.province','requests.request.informations'
                        ,'requests.request.vehicle.user.locations','requests.request.images')
                        ->with(['requests.request' => function ($query) use ($operation){
                            return $query->whereId($operation->id);
                        }])
                        ->find($seller->id);
                    event(New NewRequestForSellerEvent($data,$seller->id));
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

        $this->pushNotification('Vous avez une nouvelle demande','nouvelle demande',$seller_ids,'sellers');

        return response(['success' => true,'request_id' => $operation->id],201);
    }

}
