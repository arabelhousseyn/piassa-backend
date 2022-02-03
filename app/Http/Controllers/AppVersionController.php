<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;

class AppVersionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $version = AppVersion::where('app_type','mobile')->first();
        return response($version,200);
    }
}
