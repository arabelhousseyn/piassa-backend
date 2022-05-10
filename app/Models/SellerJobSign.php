<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerJobSign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_job_id',
        'sign_id'
    ];

    public function sign()
    {
        return $this->belongsTo(Sign::class,'sign_id')->withDefault([]);
    }
}
