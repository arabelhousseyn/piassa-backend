<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app_type' => 'mobile',
            'versioning' => '1.0.0'
        ];
    }
}
