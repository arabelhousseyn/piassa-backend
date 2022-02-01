<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserVehicleUpdateControlTest extends TestCase
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
            'technical_control' => '2023-01-02',
            'assurance' => '2023-01-02',
            'emptying' => '2023-01-02'
        ];

        $response = $this->actingAs($user)->put('api/vehicle/update_control/2',$data);
        $response->assertStatus(200);
    }
}
