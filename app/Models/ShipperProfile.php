<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipperProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipper_id',
        'province_id',
        'first_name',
        'last_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id')->withDefault();
    }
}
