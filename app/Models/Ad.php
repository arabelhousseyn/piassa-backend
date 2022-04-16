<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'type',
        'size'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getPathAttribute()
    {
        return env('DASHBOARD_APP_URL') . $this->attributes['path'];
    }
}
