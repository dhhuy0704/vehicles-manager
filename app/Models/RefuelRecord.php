<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefuelRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'odometer',
        'unit_price',
        'total_cost',
        'litres',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'odometer' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'litres' => 'decimal:2',
    ];
}