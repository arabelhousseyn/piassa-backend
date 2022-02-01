<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserVehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreVehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle_vehicle(User $user,UserVehicle $user_vehicle)
    {
        return $user->id === $user_vehicle->user_id;
    }
}
