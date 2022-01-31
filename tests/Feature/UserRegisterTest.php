<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'phone' => '0699687415',
            'password' => 'hocine.12',
            'password_confirmation' => 'hocine.12',
            'province_id' => '2',
            'full_name' => 'dsfsdfdsf',
            'gender' => 'M',
            'device_token' => '',
            'location' => '00,00',
            'has_role' => 'P'
        ];

        $response = $this->post('api/user/register',$data);
        $response->assertStatus(201);
    }
}
