<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_request_id',
        'path'
    ];

    public function request()
    {
        return $this->belongsTo(UserRequest::class)->withDefault([]);
    }

    public function getPathAttribute()
    {
        return env('APP_URL') . $this->attributes['path'];
    }
}
