<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        for ($i=0;$i<6;$i++)
        {
            $data = [
                'phone' => '0699687412',
                'password' => "hocine.12333"
            ];
            $response = $this->post('api/user/login',$data);
        }
        $response->assertStatus(403);
    }
}
