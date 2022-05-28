<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserOrderRequest;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{Admin, UserCart, UserOrder, User, Shipper, UserRequest};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;
use App\Events\NewOrderEvent;
class UserOrderController extends Controller
{

    public function list_orders()
    {
        $user_orders = User::with('orders.events')->find(Auth::id());
        $orders = $user_orders->orders->map(function($query){
            return $query->only('id','ref','type_delivery','location','created_at','events');
        });
        return response($orders,200);
    }

    public function store_order(UserOrderRequest $request)
    {
        if($request->validated())
        {
            $user_cart = UserCart::with('items.item.request.request')->findOrFail($request->user_cart_id);

            $user_cart_items = $user_cart->items;

            if(count($user_cart_items) == 0)
            {
                $message = [
                    'message' => [
                        'errors' => [
                            __('message.cart_error')
                        ]
                    ]
                ];
                return response($message,403);
            }

            DB::transaction(function () use ($request,$user_cart_items){
                UserCart::whereId($request->user_cart_id)->update([
                    'empty_at' => Carbon::now()
                ]);

                $latest_order = UserOrder::orderBy('id','desc')->first();
                if(isEmpty($latest_order))
                {
                    $user_order = Auth::user()->orders()->create([
                        'ref' => '#'. substr(Str::uuid(),0,10),
                        'type_delivery' => $request->type_delivery,
                        'amount' => $request->amount,
                        'promo_code' => null,
                        'location' => $request->location
                    ]);
                }else{
                    $user_order = Auth::user()->orders()->create([
                        'ref' => '#'. substr(Str::uuid(),0,10),
                        'type_delivery' => $request->type_delivery,
                        'promo_code' => null,
                        'location' => $request->location
                    ]);
                }

                $user_order_info = UserOrder::with('user')->find($user_order->id);

                foreach ($user_cart_items as $user_cart_item) {
                    UserRequest::where('id',$user_cart_item->item->request->request->id)->update([
                        'expired_at' => Carbon::now()
                    ]);


                    $user_order_info->items()->create([
                        'seller_suggestion_id' => $user_cart_item->seller_suggestion_id
                    ]);
                }

                $shippers = Shipper::with('profile')->get();
                foreach ($shippers as $shipper)
                {
                    if($shipper->profile->province_id == $user_order_info->user->profile->province_id)
                    {
                        $user_order_info->shipperUserOrder()->create([
                            'shipper_id' => $shipper->id
                        ]);
                    }
                }
                $data = UserOrder::with('shipperUserOrder','items')->find($user_order->id);
                event(new NewOrderEvent($data));
                $admins = Admin::all();

                Notification::send($admins,new NewOrderNotification($data));
            });


                return response(['success' => true],201);

        }
    }

    public function order_details($id)
    {
        $user_order = UserOrder::with('items.item.request.request.informations','events','invoice')->find($id);
        return response($user_order->only('id','ref','type_delivery','location','items','created_at','events','invoice'),200);
    }
}
