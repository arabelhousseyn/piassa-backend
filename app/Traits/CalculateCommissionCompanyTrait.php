<?php

namespace App\Traits;
use App\Models\UserOrder;
trait CalculateCommissionCompanyTrait
{
    public function CalculateCommissionCompany($user_order_id)
    {
        $total = 0;
        $user_order = UserOrder::with('items.item.request.request.type')->find($user_order_id);
        $amount = $user_order->amount;
        foreach ($user_order->items as $item) {
            $percent = $item->item->request->request->type->percent;
            $total += ($amount * $percent) / 100;
        }
        return $total;
    }
}
