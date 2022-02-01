<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserVehicleControlRequest;
use App\Http\Requests\UserVehicleControlRequest;
use App\Http\Requests\VehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,UserVehicle};
class VehicleController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.chassis')->except(['index','show','create','edit','update','destory','store_control','update_control']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['vehicle'=>function($query){
            return $query->with('sign')->orderBy('created_at','desc');
        }])->find(Auth::id());
        return response($user->vehicle,200);
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
    public function store(VehicleRequest $request)
    {
        if($request->validated())
        {
            Auth::user()->vehicle()->create([
              'sign_id' => $request->sign_id,
              'model' => $request->model,
              'year' => $request->year,
              'motorization' => $request->motorization,
              'chassis_number' => $request->chassis_number,
            ]);

            if($request->has('location'))
            {
                Auth::user()->locations()->create([
                    'location' => $request->location
                ]);
            }
            return response(['success' => true],201);
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

    public function store_control(UserVehicleControlRequest $request)
    {
        if($request->validated())
        {
                $user_vehicle = UserVehicle::find($request->user_vehicle_id);
                $user_vehicle->control()->create($request->only(['technical_control','assurance','emptying']));
                return response(['success' => true],201);
        }
    }

    public function update_control($user_vehicle_id,UpdateUserVehicleControlRequest $request)
    {
        if($request->validated())
        {
            try {
                $user_vehicle = UserVehicle::findOrFail($user_vehicle_id);
                $user_vehicle->control()->update($request->only(['technical_control','assurance','emptying']));
                return response(['success' => true],200);
            }catch (\Exception $e)
            {
                return response(['message' => 'not found'],404);
            }
        }
    }
}
