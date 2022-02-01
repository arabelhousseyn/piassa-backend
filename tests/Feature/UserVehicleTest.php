<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class UserVehicleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::find(116);
        $data = [
            'sign_id' => 1,
            'model' => 'das',
            'year' => '2021',
            'motorization' => '223',
            'chassis_number' => 'adsad'
        ];
        $response = $this->actingAs($user)->post('api/vehicle',$data);
        $response->assertStatus(201);
    }
}
