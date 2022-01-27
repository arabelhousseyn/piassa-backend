<?php


namespace App\Traits;

use App\Models\Shipper;

trait CalculateCommissionShipperTrait
{
    public function CalculateCommissionShipper($distance, $type)
    {
        $calculated = 0;
        $km = $distance['1-2']['km'];

        if($km <= Shipper::KM)
        {
            $calculated = 500;
        }else{
            $rest = $km - Shipper::KM;
            $calculated = round($rest) * Shipper::PRICE_KM;
        }

        switch ($type)
        {
            case 'E' :
                $calculated += 500;
                break;
        }

        return $calculated;
    }
}
