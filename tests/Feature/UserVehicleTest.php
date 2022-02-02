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
        $user = User::find(1);
        $data = [
            'sign_id' => 1,
            'model' => 'AUDI A3',
            'year' => '2021',
            'motorization' => '#4812',
            'chassis_number' => 'asdadasd'
        ];
        $response = $this->actingAs($user)->post('api/vehicle',$data);
        $response->assertStatus(201);
    }
}
