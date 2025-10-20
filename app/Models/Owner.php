<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Owner extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    protected $casts = [
        'gender' => 'string',
    ];
}
