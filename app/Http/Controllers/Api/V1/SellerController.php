<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Seller,SellerRequest};
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function insert_location($location)
    {
        $operation = Auth::user()->profile()->update([
            'location' => $location
        ]);

        if($operation)
        {
            return response(['success' => true],200);
        }
    }

    public function list_requests()
    {
        $seller = Seller::with('requests','requests.request','requests.request.informations','requests.request.vehicle','requests.request.vehicle.sign')->find(Auth::id());
        $requests = $seller->requests->map(function($map){
            return collect($map->only('request'));
        });
        return response($requests,200);
    }
}
