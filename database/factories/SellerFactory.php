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
            $conditions = ['new','used'];
            $seller->profile()->create([
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'commercial_name' => $this->faker->name,
                'province_id' => 1,
                'device_token' => null,
                'location' => '36.7669,2.9602',
                'condition' => $conditions[rand(0,1)]
            ]);

           $job = $seller->jobs()->create([
                'job' => $this->faker->jobTitle,
            ]);

           $job->signs()->create([
               'sign_id' => 1
           ]);

           $job->types()->create([
               'type_id' => 1
           ]);

            $seller->phones()->create([
                'name' => $this->faker->name,
                'phone' => $this->faker->numerify('##########')
            ]);
        });
    }


}
