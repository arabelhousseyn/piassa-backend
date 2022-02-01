<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class UserStoreRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::find(11);

        $data = [
            'user_vehicle_id' => '1',
            'qt' => '2',
            'type_id' => '1',
            'mark' => 'hiho',
            'piston' => 'test'
        ];

        $response = $this->actingAs($user)->post('/api/user/request',$data);

        $response->assertStatus(201);
    }
}
