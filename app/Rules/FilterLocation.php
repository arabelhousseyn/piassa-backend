<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class FilterLocation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data  = explode(',',$value);

        if(count($data) !== 2)
        {
            return false;
        }else{
            if(doubleval($data[0]) == 0 || doubleval($data[1]) == 0)
            {
                return false;
            }else{
                if($data[1] == doubleval($data[1]) && $data[0] == doubleval($data[0]))
                {
                    return true;
                }

                return false;
            }
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('message.location_error');
    }
}
