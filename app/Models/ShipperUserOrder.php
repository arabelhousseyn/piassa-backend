<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipperUserOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_order_id',
        'shipper_id'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function order()
    {
        return $this->belongsTo(UserOrder::class,'user_order_id')->withDefault();
    }
}
