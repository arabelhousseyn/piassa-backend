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
            $days_in_emptying = $this->CalculateDiff($control->emptying);

            if($days_in_technical_control  == 2)
            {
                $message = 'Vérifier votre contrôle technique pour véhicule ' . $control->vehicle->model;
                $this->push('Contrôle technique',$message,$control->vehicle->user->id);
            }

            if($days_in_assurance  == 2)
            {
                $message = 'Vérifier votre assurance pour véhicule ' . $control->vehicle->model;
                $this->push('Assurance',$message,$control->vehicle->user->id);
            }

            if($days_in_emptying  == 2)
            {
                $message = 'Vérifier votre vidange pour véhicule ' . $control->vehicle->model;
                $this->push('Vidange',$message,$control->vehicle->user->id);
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
