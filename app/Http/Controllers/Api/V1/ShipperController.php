<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{Shipper};
use Illuminate\Support\Str;
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
        return $final;

    }
}
