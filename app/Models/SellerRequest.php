<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'request_id',
        'suggest_him_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class)->withDefault();
    }

}
