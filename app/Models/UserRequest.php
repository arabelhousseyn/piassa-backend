<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_vehicle_id',
        'qt',
        'type_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'qt' => 'integer'
    ];

    public function vehicle()
    {
        return $this->belongsTo(UserVehicle::class,'user_vehicle_id')->withDefault();
    }

    public function informations()
    {
        return $this->morphMany(Attribute::class,'attributeable');
    }

    public function suggestions()
    {
        return $this->hasMany(SellerRequest::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class,'type_id')->withDefault();
    }
}
