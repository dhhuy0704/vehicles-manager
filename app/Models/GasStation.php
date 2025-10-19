<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GasStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'lat',
        'long',
    ];

    protected $casts = [
        'lat' => 'float:8',
        'long' => 'float:8',
    ];

    protected $attributes = [
        'lat' => null,
        'long' => null,
    ];
}