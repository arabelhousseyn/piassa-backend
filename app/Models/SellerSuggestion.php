<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerSuggestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_request_id',
        'mark',
        'price',
        'available_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}
