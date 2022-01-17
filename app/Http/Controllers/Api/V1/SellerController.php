<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreSellerSuggestionRequest;
use App\Models\{Seller,SellerRequest};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
        $seller = Seller::with('requests.request.vehicle.sign','requests.request.vehicle.user.profile.province')
            ->with(['requests' => function($query){
                return $query->whereNull('suggest_him_at');
            }])->find(Auth::id());
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


    public function store_seller_suggestion(StoreSellerSuggestionRequest $request)
    {
        SellerRequest::whereId($request->seller_request_id)->update([
            'suggest_him_at' => Carbon::now()
        ]);

        $seller_request = SellerRequest::find($request->seller_request_id);
        $seller_request->suggestion()->create([
            'mark' => $request->mark,
            'price' => $request->price,
            'available_at' => $request->available_at,
        ]);
        return response(['success' => true],200);
    }
}
