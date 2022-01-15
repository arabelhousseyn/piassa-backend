<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Seller;
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
}
