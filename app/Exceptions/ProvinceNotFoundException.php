<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ProvinceNotFoundException extends Exception
{
    public function report()
    {
        Log::debug('province not found');
    }
}
