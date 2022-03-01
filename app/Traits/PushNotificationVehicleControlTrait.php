<?php

namespace App\Traits;
use App\Models\UserVehicleControl;
use Illuminate\Support\Carbon;
use App\Traits\SendPushNotificationTrait;
trait PushNotificationVehicleControlTrait
{
    use SendPushNotificationTrait;
    public function index()
    {
        $controls = UserVehicleControl::with('vehicle.user')->get();
        foreach ($controls as $control) {
            $days_in_technical_control = $this->CalculateDiff($control->technical_control);
            $days_in_assurance = $this->CalculateDiff($control->assurance);

            if($days_in_technical_control  == 2)
            {
                $message = 'Vérifier votre contrôle technique pour véhicule ' . $control->vehicle->model;
                $this->push('Piassa',$message,$control->vehicle->user->id);
            }

            if($days_in_assurance  == 2)
            {
                $message = 'Vérifier votre assurance pour véhicule ' . $control->vehicle->model;
                $this->push('Piassa',$message,$control->vehicle->user->id);
            }
        }
    }

    public function CalculateDiff($date1)
    {
        $value1 = Carbon::parse($date1);
        $now = Carbon::now();
        return $value1->diffInDays($now);
    }
}
