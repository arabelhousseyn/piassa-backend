<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Models\{Seller,SellerRequest};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $seller = Seller::with('requests.request.vehicle.sign','requests.request.vehicle.user.profile.province')->find(Auth::id());
        $requests = $seller->requests->map(function($map){
            return $map->only('request');
        });
        return response($requests,200);
    }

    public function count_seller_requests_by_type($types)
    {
        $seller = Seller::with('requests.request')->find(Auth::id());
        $data = [];
        $extract = explode(',',$types);
        foreach ($extract as $type)
        {
            $count = 0;
            foreach ($seller->requests as $request)
            {
                if($request->request->type == Str::upper($type))
                {
                    $count++;
                }
            }
            $data[] = [
                'type' => $type,
                'count' => $count
            ];
        }

        return response($data,200);

    }
}
