<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserOrderRequest;

class UserOrderController extends Controller
{

    public function list_orders()
    {

    }

    public function store_order(UserOrderRequest $request)
    {
        if($request->validated())
        {

        }
    }

    public function order_details($id)
    {

    }
}
