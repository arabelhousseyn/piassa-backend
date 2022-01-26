<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Sign};
use Illuminate\Support\Str;
class VehicleController extends Controller
{
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
            // chassis number prefix compatible with the prefix of sign
            $sign = Sign::find($request->sign_id);
            if(!Str::startsWith($request->chassis_number,$sign->prefix))
            {
                $message = [
                    'message' => [
                        'errors' => [
                            'chassis_number' => [
                                'N° châssis incorrect'
                            ]
                        ]
                    ]
                ];
                return response($message,403);
            }
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
            return response(['success' => true],200);
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
