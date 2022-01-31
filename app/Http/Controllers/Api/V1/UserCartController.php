<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCartRequest;
use Illuminate\Support\Facades\Auth;
Use App\Models\{UserCart,UserCartItem,User, SellerSuggestion};
use Illuminate\Support\Carbon;
class UserCartController extends Controller
{

    public function info_cart()
    {
        $carts = User::with(['carts' => function($query){
            return $query->where('empty_at',null)->latest()->first();
        }])->find(Auth::id());
        if(@$carts->carts[0])
        {
            $user_cart = UserCart::with('items.item.request.request')->find($carts->carts[0]->id);
            return response($user_cart->only('id','full_at','is_empty','items'),200);
        }else{
            return response([],200);
        }
    }

    public function store_cart(StoreUserCartRequest $request)
    {
        if($request->validated())
        {
            $carts = User::with(['carts' => function($query){
                return $query->latest()->first();
            }])->find(Auth::id());

            $seller_suggestion = SellerSuggestion::find($request->seller_suggestion_id);
            $seller_suggestion->update([
                'taken_at' => Carbon::now()
            ]);

            if(count($carts->carts) > 0)
            {
                if($carts->carts[0]->empty_at !== null)
                {
                    $cart_info = Auth::user()->carts()->create([
                        'full_at' => Carbon::now()
                    ]);

                    $cart = UserCart::find($cart_info->id);

                    $cart->items()->create([
                        'seller_suggestion_id' => $request->seller_suggestion_id
                    ]);
                    return response(['success' => true],201);
                }else{
                    $cart = UserCart::find($carts->carts[0]->id);

                    $cart->items()->create([
                        'seller_suggestion_id' => $request->seller_suggestion_id
                    ]);
                    return response(['success' => true],201);
                }
            }else{
                $cart_info = Auth::user()->carts()->create([
                    'full_at' => Carbon::now()
                ]);

                $cart = UserCart::find($cart_info->id);

                $cart->items()->create([
                    'seller_suggestion_id' => $request->seller_suggestion_id
                ]);
                return response(['success' => true],201);
            }
        }
    }

    public function destory_items_cart($id)
    {
        $user_cart_item = UserCartItem::find($id);

        $seller_suggestion = SellerSuggestion::find($user_cart_item->seller_suggestion_id);
        $seller_suggestion->update([
            'taken_at' => null
        ]);

        $delete = $user_cart_item->deleteOrFail();
        if($delete)
        {
            return response(['success' => true],200);
        }
    }

    public function destory_cart($id)
    {
        $user_cart = UserCart::with('items')->find($id);

        foreach ($user_cart->items as $item)
        {
            $cart_item = UserCartItem::find($item->id);
            $cart_item->deleteOrFail();
            $seller_suggestion = SellerSuggestion::find($item->seller_suggestion_id);
            $seller_suggestion->update([
                'taken_at' => null
            ]);
        }

        $delete = $user_cart->deleteOrFail();
        if($delete)
        {
            return response(['success' => true],200);
        }
    }
}
