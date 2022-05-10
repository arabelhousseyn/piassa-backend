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
        'job'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function signs()
    {
        return $this->hasMany(SellerJobSign::class);
    }

    public function types()
    {
        return $this->hasMany(SellerJobType::class);
    }

}
