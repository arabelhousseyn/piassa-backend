<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;
use App\Models\{Shipper, SellerSuggestion, UserOrder,ShipperUserOrder};
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

    public function recover_order($order_user_id,$coord)
    {
        $user_order = UserOrder::findOrFail($order_user_id);

        if(count($user_order->events) > 0)
        {
            $message = [
                'message' => [
                    'errors' => [
                        'Operation deja effectué'
                    ]
                ]
            ];
            return response($message,403);
        }

        $user_order->events()->create([
            'event' => 'R'
        ]);

        $shipper_user_order = $user_order->shipperUserOrder;
        $op = ShipperUserOrder::find($shipper_user_order->id);
         $op->commission()->create([
            'start_coordination' => $coord
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

    public function delivery_order($order_user_id,$coord)
    {
        $user_order = UserOrder::with('items')->findOrFail($order_user_id);

        if(count($user_order->events) > 1)
        {
            $message = [
                'message' => [
                    'errors' => [
                        'Operation deja effectué'
                    ]
                ]
            ];
            return response($message,403);
        }

        $user_order->events()->create([
            'event' => 'D'
        ]);

        $shipper_user_order = $user_order->shipperUserOrder;
        $op = ShipperUserOrder::with('commission')->find($shipper_user_order->id);

        $commission = $op->commission;
        $start_coord = explode(',',$commission->start_coordination);
        $end_coord = explode(',',$coord);

        $distance = GeoFacade::setPoint([doubleval($start_coord[0]), doubleval($start_coord[1])])
            ->setOptions(['units' => ['km']])
            ->setPoint([doubleval($end_coord[0]), doubleval($end_coord[1])])
            ->getDistance();

        $amount = $this->CalculateAmount($distance,$user_order->type_delivery);

        $op->commission()->update([
            'end_coordination' => $coord,
            'amount' => $amount
        ]);

        foreach ($user_order->items as $item) {
           SellerSuggestion::whereId($item->seller_suggestion_id)->update([
                'delivered_at' => Carbon::now()
            ]);
        }

        return response(['success' => true],200);
    }

    public function CalculateAmount($distance, $type)
    {
        $calculated = 0;
        $km = $distance['1-2']['km'];

        if($km <= Shipper::KM)
        {
            $calculated = 500;
        }else{
            $rest = $km - Shipper::KM;
            $calculated = round($rest) * Shipper::PRICE_KM;
        }

        switch ($type)
        {
            case 'E' :
                $calculated += 500;
                break;
        }

        return $calculated;
    }
}
