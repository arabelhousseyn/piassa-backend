<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCartItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_cart_id',
        'mark',
        'price',
        'qt',
        'available_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


}
