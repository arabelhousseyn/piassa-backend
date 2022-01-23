<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreSellerSuggestionRequest;
use App\Models\{Seller,SellerRequest};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Events\NewSuggestionEvent;
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
        return response(['success' => false],433);
    }

    public function list_requests()
    {
        $seller = Seller::with('requests.request.vehicle.sign','requests.request.vehicle.user.profile.province','requests.request.informations'
        ,'requests.request.vehicle.user.locations')
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
        $marks = explode(',',$request->marks);
        $prices = explode(',',$request->prices);
        $available_at = explode(',',$request->available_at);

        if(count($marks) == count($prices) && count($marks) == count($available_at) &&
        count($prices) == count($available_at))
            {
                SellerRequest::whereId($request->seller_request_id)->update([
                    'suggest_him_at' => Carbon::now()
                ]);

                $seller_request = SellerRequest::find($request->seller_request_id);
                for ($i=0;$i<count($marks);$i++)
                {
                    $seller_request->suggestion()->create([
                        'mark' => $marks[$i],
                        'price' => $prices[$i],
                        'available_at' => $available_at[$i],
                    ]);
                }
                $data = SellerRequest::with('suggestion')->find($request->seller_request_id);
                event(new NewSuggestionEvent($data));
                return response(['success' => true],200);

            }else{
            $message = [
                'message' => [
                    'errors' => [
                        'Erreur veuillez réessayer.'
                    ]
                ]
            ];
            return response($message,403);
        }
    }
}
