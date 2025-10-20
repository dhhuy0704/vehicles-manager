<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RefuelRecord;
use Illuminate\Http\Request;

class LastRefuelRecordController extends Controller
{
    public function getLastRecord(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        
        $lastRecord = RefuelRecord::where('vehicle_id', $vehicleId)
            ->latest()
            ->first();
            
        if (!$lastRecord) {
            return response()->json([
                'gas_station_id' => null,
                'price_per_unit' => null,
                'odometer' => null,
            ]);
        }
        
        return response()->json([
            'gas_station_id' => $lastRecord->gas_station_id,
            'price_per_unit' => $lastRecord->price_per_unit,
            'odometer' => $lastRecord->odometer,
        ]);
    }
}