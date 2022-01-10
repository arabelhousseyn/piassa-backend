<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'location'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
