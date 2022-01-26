<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{Shipper, User, UserOrder};
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
class ShipperController extends Controller
{
    public function index()
    {
        $data = Shipper::with('orderRequests.order.items.item.request.request.informations')->
        with(['orderRequests.order' => function($query){
            return $query->whereNull('confirmed_at');
        }])->find(Auth::id());
        $subset = $data->orderRequests->map(function ($filter){
            return $filter->only('id','created_at','order');
        });
        return response($subset,200);
    }

    public function count_orders_by_delivery_type($types)
    {
        $final = [];
        $dataEx = explode(',',$types);

        $data = Shipper::with('orderRequests.order')->find(Auth::id());
        for ($i=0;$i<count($dataEx);$i++)
        {
            $count = 0;
            foreach ($data->orderRequests as $value)
            {
                if($value->order->type_delivery == Str::upper($dataEx[$i]))
                {
                    $count++;
                }
            }
            $final[] = [
                'type' => Str::upper($dataEx[$i]),
                'count' => $count
            ];
        }
        return response($final,200);
    }

    public function confirm_order($order_user_id)
    {
        $check = UserOrder::find($order_user_id);
        if($check->confirmed_at !== null)
        {
            $message = [
                'message' => [
                    'errors' => [
                        'Commande déjà confirmée.'
                    ]
                ]
            ];
            return response($message,403);
        }

        UserOrder::whereId($order_user_id)->update([
            'confirmed_at' => Carbon::now()
        ]);
        return response(['success' => true],200);
    }

    public function get_recovery_orders()
    {
        $data = Shipper::with('orderRequests.order.items.item.request.request.informations')->
        with(['orderRequests.order' => function($query){
            return $query->whereNotNull('confirmed_at');
        }])->with('orderRequests.order.events')->find(Auth::id());

        $subset = $data->orderRequests->map(function ($filter){
            return $filter->only('id','created_at','order');
        });

        return response($subset,200);
    }

    public function recover_order($order_user_id)
    {
        $user_order = UserOrder::findOrFail($order_user_id);

        $user_order->events()->create([
            'event' => 'R'
        ]);

        return response(['success' => true],200);
    }

    public function get_delivery_orders()
    {
        $data = Shipper::with('orderRequests.order.items.item.request.request.informations')->
        with(['orderRequests.order' => function($query){
            return $query->whereNotNull('confirmed_at');
        }])->with('orderRequests.order.events')->with('orderRequests.order.user.profile')
            ->with('orderRequests.order.user.locations')->find(Auth::id());

        $subset = $data->orderRequests->map(function ($filter){
            return $filter->only('id','created_at','order');
        });

        return response($subset,200);
    }

    public function delivery_order($order_user_id)
    {
        $user_order = UserOrder::findOrFail($order_user_id);

        $user_order->events()->create([
            'event' => 'D'
        ]);

        return response(['success' => true],200);
    }
}
