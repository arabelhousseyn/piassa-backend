<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdController extends Controller
{
    public function index(string $dim)
    {
        $split = explode('x',$dim);

        if(count($split) == 2 && is_numeric($split[0]) && is_numeric($split[1]))
        {
            $ad = Ad::where([['type','MS'],['size',$dim]])->first();
            if($ad)
            {
                return response(['data' => $ad],200);
            }else{

                $response = Http::get(env('DASHBOARD_APP_URL') . 'api/ads/resize/' . $split[0] . '/' . $split[1]);
                return $response->json();
            }
        }else{
            $error = [
                'errors' =>[
                    'dimension' => 'Format invalide.'
                ]
            ];
            return response($error,422);
        }
    }
}
