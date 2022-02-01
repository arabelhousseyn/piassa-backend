<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class UserVehicleStoreControlTest extends TestCase
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
            'user_vehicle_id' => '2222',
            'technical_control' => '2023-01-02',
            'assurance' => '2023-01-02',
            'emptying' => '2023-01-02'
        ];

        $response = $this->actingAs($user)->post('api/vehicle/store_control',$data);
        $response->assertStatus(201);

    }
}
