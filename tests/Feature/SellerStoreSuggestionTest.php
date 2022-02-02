<?php

namespace Tests\Feature;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SellerStoreSuggestionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $seller = Seller::find(1);
        $data = [
            'seller_request_id' =>11,
            'marks' => 'asdasd,asd',
            'prices' => '12,12',
            'available_at' => '2022-02-13'
        ];

        $response = $this->actingAs($seller)->post('api/seller/store_seller_suggestion',$data);
        $response->assertStatus(201);
    }
}
