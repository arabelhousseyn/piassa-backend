<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestUserRequest;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use App\Services\RequestUserService;
class RequestUserController extends Controller
{

    protected $request_user_service;


    public function __construct(RequestUserService $request_user_service)
    {
        $this->request_user_service = $request_user_service;
    }

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
            return $this->request_user_service->store($request);
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

    public function userRequestInformationsDetails($user_request_id)
    {
        try {
            $user_request = UserRequest::with('informations','images')->findOrFail($user_request_id);
            return response(['informations' => $user_request->informations],200);
        }catch (\Exception $exception)
        {
            return response(['message' => $exception->getMessage()],404);
        }
    }
}
