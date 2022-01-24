<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\{UserCart,UserOrder,User};
use Illuminate\Support\Carbon;
use function PHPUnit\Framework\isEmpty;

class UserOrderController extends Controller
{

    public function list_orders()
    {
        $user_orders = User::with('orders')->find(Auth::id());
        $orders = $user_orders->orders->map(function($query){
            return $query->only('id','ref','type_delivery','location','created_at');
        });
        return response($orders,200);
    }

    public function store_order(UserOrderRequest $request)
    {
        if($request->validated())
        {
            $user_cart = UserCart::with('items')->findOrFail($request->user_cart_id);

            $user_cart_items = $user_cart->items;

            if(count($user_cart_items) == 0)
            {
                $message = [
                    'message' => [
                        'errors' => [
                            'Le panier est vide'
                        ]
                    ]
                ];
                return response($message,403);
            }

            UserCart::whereId($request->user_cart_id)->update([
                'empty_at' => Carbon::now()
            ]);

            $latest_order = UserOrder::orderBy('id','desc')->first();
            if(isEmpty($latest_order))
            {
                $user_order = Auth::user()->orders()->create([
                    'ref' => '#'.str_pad("1", STR_PAD_LEFT),
                    'type_delivery' => $request->type_delivery,
                    'promo_code' => null,
                    'location' => $request->location
                ]);
            }else{
                $user_order = Auth::user()->orders()->create([
                    'ref' => '#'.str_pad($latest_order->id + 1, 8, "0", STR_PAD_LEFT),
                    'type_delivery' => $request->type_delivery,
                    'promo_code' => null,
                    'location' => $request->location
                ]);
            }

            $user_order_info = UserOrder::find($user_order->id);

            foreach ($user_cart_items as $user_cart_item) {
                $user_order_info->items()->create([
                    'seller_suggestion_id' => $user_cart_item->seller_suggestion_id
                ]);
            }
            return response(['success' => true],200);
        }
    }

    public function order_details($id)
    {
        $user_order = UserOrder::with('items.item.request.request.informations')->find($id);
        return response($user_order->only('id','ref','type_delivery','location','items','created_at'),200);
    }
}
