<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'owner_id',
        'type',
        'name',
        'manufacturer',
        'model',
        'year',
        'fuel_capacity',
        'status',
        'note',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    const TYPES = [
        'car'    => 'Car',
        'truck'  => 'Truck',
        'van'    => 'Van',
        'camper' => 'Camper',
    ];

    const STATUSES = [
        'active' => 'Active',
        'broken' => 'Broken',
        'sold'   => 'Sold',
    ];
}
