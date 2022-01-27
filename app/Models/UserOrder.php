<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref',
        'user_id',
        'type_delivery',
        'amount',
        'location',
        'promo_code',
        'confirmed_at'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'amount' => 'double'
    ];

    public function items()
    {
        return $this->hasMany(UserOrderItem::class);
    }

    public function events()
    {
        return $this->hasMany(UserOrderEvent::class);
    }

    public function shipperUserOrder()
    {
        return $this->hasOne(ShipperUserOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function invoice()
    {
      return $this->hasOne(UserOrderInvoice::class);
    }
}
