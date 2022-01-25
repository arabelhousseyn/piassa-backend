<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{Shipper};
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
}
