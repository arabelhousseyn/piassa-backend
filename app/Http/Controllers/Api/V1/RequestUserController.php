<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestUserRequest;
use Illuminate\Http\Request;
use App\Models\{UserRequest,Seller,User};
use Illuminate\Support\Facades\Auth;
use \KMLaravel\GeographicalCalculator\Facade\GeoFacade;
use Illuminate\Support\Str;
use App\Events\NewRequestEvent;
class RequestUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestUserRequest $request)
    {
        if($request->validated())
        {
            $data = $request->except('type','user_vehicle_id');

            $operation = UserRequest::create([
                'user_vehicle_id' => $request->user_vehicle_id,
                'type' => $request->type
            ]);

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
                        if($job->type == Str::upper($operation->type))
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

            return response(['success' => true,'request_id' => $operation->id],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
