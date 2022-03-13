<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'job',
        'type_id',
        'sign_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class,'type_id')->withDefault();
    }
}
