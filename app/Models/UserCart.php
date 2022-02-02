<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_at',
        'empty_at'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['is_empty'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

    public function items()
    {
        return $this->hasMany(UserCartItem::class);
    }

    protected function getIsEmptyAttribute()
    {
        return ($this->attributes['empty_at'] !== null) ? true : false;
    }
}
