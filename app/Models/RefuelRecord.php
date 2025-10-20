<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefuelRecord extends Model
{
    use HasFactory, HasUuid;

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
        'date'          => 'date',
        'odometer'      => 'integer',
        'price_per_unit'=> 'decimal:3',
        'total_cost'    => 'decimal:3',
        'amount'        => 'decimal:2',
    ];

    public function getFormattedTimeAttribute()
    {
        if (!$this->time) return null;
        
        $utcTime = \Carbon\Carbon::createFromFormat('H:i:s', $this->time, 'UTC');
        return $utcTime->setTimezone(config('app.display_timezone', 'UTC'))->format('H:i');
    }

    public function gasStation()
    {
        return $this->belongsTo(GasStation::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}