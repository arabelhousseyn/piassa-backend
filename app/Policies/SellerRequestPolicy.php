<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\SellerRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle_request(Seller $seller,SellerRequest $seller_request)
    {
        return $seller->id === $seller_request->seller_id;
    }
}
