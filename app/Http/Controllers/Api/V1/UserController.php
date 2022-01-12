<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function insert_location($location)
    {
        Auth::user()->locations()->create([
            'location' => $location
        ]);
        return response(['success' => true],200);
    }
}
