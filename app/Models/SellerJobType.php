<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerJobType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_job_id',
        'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class,'type_id')->withDefault([]);
    }
}
