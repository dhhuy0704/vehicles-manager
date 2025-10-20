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
        'price_per_unit',
        'amount',
        'total_cost',
        'gas_station_id',
        'vehicle_id',
    ];

    protected $casts = [
        'date'       => 'date',
        'time'       => 'datetime',
        'odometer'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'litres'     => 'decimal:2',
    ];

    public function gasStation()
    {
        return $this->belongsTo(GasStation::class);
    }
}