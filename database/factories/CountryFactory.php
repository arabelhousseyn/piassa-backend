<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Algeria',
            'code' => '213'
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Country $country){
                $country->provinces()->create([
                    'name' => 'Adrar',
                    'code' => '01'
                ]);
        });
    }
}
