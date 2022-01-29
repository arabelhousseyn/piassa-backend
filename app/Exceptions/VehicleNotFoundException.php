<?php

namespace App\Exceptions;

use Exception;

class VehicleNotFoundException extends Exception
{
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['message' => 'not found'],404);
    }
}
