<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'AUDI',
            'logo' => '###',
            'prefix' => 'WAU'
        ];
    }
}
