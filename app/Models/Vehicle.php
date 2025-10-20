<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory, HasUuid;
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
