<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVehicleControl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_vehicle_id',
        'technical_control',
        'assurance',
        'emptying'
    ];

    protected $hidden = [
        'created_at',
        'deleted_at'
    ];
}
