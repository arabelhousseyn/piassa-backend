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
        'location',
        'promo_code'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function items()
    {
        return $this->hasMany(UserOrderItem::class);
    }
}
