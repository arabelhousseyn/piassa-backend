<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipperCommission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_order_id',
        'start_coordination',
        'end_coordination',
        'amount'
    ];

    protected $hidden = [
        'created_at',
        'deleted_at'
    ];
}
