<?php

namespace Database\Factories;

use App\Models\Shipper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ShipperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail,
            'email_verified_at' => null,
            'phone_verified_at' => null,
            'password' => Hash::make('password')
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Shipper $shipper){
            $shipper->profile()->create([
                'province_id' => 1,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'device_token' => null
            ]);
        });
    }
}
