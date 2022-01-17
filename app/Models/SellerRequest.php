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
        'user_request_id',
        'suggest_him_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function request()
    {
        return $this->belongsTo(UserRequest::class,'user_request_id')->withDefault();
    }

    public function suggestion()
    {
        return $this->hasOne(SellerSuggestion::class)->withDefault();
    }

}
