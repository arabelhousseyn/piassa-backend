<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'value',
        'attributeable_type',
        'attributeable_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'attributeable_type',
        'attributeable_id'
    ];

    public function attributeable()
    {
        return $this->morphTo();
    }
}
