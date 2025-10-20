<?php

namespace App\Http\Controllers;

use App\Models\RefuelRecord;
use App\Models\Vehicle;
use App\Models\GasStation;
use Illuminate\Http\Request;

class QuickRefuelController extends Controller
{
    public function create()
    {
        $vehicles = Vehicle::all();
        $gasStations = GasStation::all();
        
        // Get the last refuel record
        $lastRecord = RefuelRecord::latest()->first();
        
        $defaultValues = [
            'gas_station_id' => $lastRecord ? $lastRecord->gas_station_id : null,
            'price_per_unit' => $lastRecord ? $lastRecord->price_per_unit : null,
            'odometer'       => $lastRecord ? $lastRecord->odometer : null,
        ];
        
        return view('quick-refuel', compact('vehicles', 'gasStations', 'defaultValues'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'vehicle_id'     => 'required|exists:vehicles,id',
            'gas_station_id' => 'required|exists:gas_stations,id',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric',
            'odometer'       => 'required|numeric|min:0',
            'total_cost'     => 'required|numeric|min:0',
        ]);

        RefuelRecord::create($validated);

        return redirect()->route('quick-refuel')->with('success', 'Refuel record created successfully!');
    }
}