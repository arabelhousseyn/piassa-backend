<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
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
            'email' => $this->faker->unique()->safeEmail(),
            'otp' => null,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user){
            $user->profile()->create([
                'province_id' => 1,
                'full_name' => $this->faker->firstNameFemale,
                'gender' => 'W',
                'device_token' => null,
            ]);

            $user->locations()->create([
                'location' => '36.7944,3.0524'
            ]);
            $user->assignRole('P');
        });
    }
}
