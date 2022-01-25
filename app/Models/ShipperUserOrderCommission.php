<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipperUserOrderCommission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipper_user_order_id',
        'start_coordination',
        'end_coordination',
        'amount'
    ];

    protected $hidden = [
        'created_at',
        'deleted_at'
    ];

    public function userOrder()
    {
        return $this->belongsTo(ShipperUserOrder::class,'shipper_user_order_id')->withDefault();
    }
}
