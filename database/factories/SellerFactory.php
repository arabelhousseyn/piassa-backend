<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerFactory extends Factory
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
            'email' => $this->faker->email,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Seller $seller){
            $seller->profile()->create([
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'commercial_name' => $this->faker->name,
                'device_token' => null,
                'location' => '0,0'
            ]);

            $seller->jobs()->create([
                'job' => $this->faker->jobTitle,
                'type_id' => 1
            ]);

            $seller->phones()->create([
                'phone' => $this->faker->numerify('##########')
            ]);
        });
    }


}
