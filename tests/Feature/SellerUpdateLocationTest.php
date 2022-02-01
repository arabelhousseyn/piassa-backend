<?php

namespace Tests\Feature;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class SellerUpdateLocationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $seller = Seller::find(1);
        $response = $this->actingAs($seller)->get('api/seller/insert_location?location=12,12');
        $response->assertStatus(200);
    }
}
