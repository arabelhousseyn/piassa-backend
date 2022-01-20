<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCartRequest;
Use App\Models\{UserCart,UserCartItem};
class UserCartController extends Controller
{

    public function info_cart()
    {

    }

    public function store_cart(StoreUserCartRequest $request)
    {
        if($request->validated())
        {

        }
    }

    public function destory_items_cart(UserCartItem $user_cart_item)
    {

    }

    public function destory_cart(UserCart $user_cart)
    {

    }
}
