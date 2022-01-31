<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellerLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'phone' => '',
            'password' => ""
        ];
        $response = $this->post('api/seller/login',$data);

        $response->assertStatus(403);
    }
}
