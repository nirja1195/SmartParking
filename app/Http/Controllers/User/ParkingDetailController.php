<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParkingDetailController extends Controller
{
    /**
     * Requirement #19: Parking Location Details (/parking/{id}).
     */
    public function show(int $id): View
    {
        $location = ParkingLocation::where('status', 'Active')
            ->with(['zones.slots'])
            ->findOrFail($id);

        return view('user.parking-detail', compact('location'));
    }

    /**
     * Requirement #20: Slot Selection (/parking/{id}/zone/{zone}).
     * Displays actual database slots on a visual grid.
     */
    public function showZoneSlots(int $id, int $zoneId, Request $request): View
    {
        $location = ParkingLocation::where('status', 'Active')->findOrFail($id);
        $zone = ParkingZone::where('parking_location_id', $location->id)->findOrFail($zoneId);

        // Fetch slots ordered by slot_number
        $slots = ParkingSlot::where('zone_id', $zone->id)
            ->orderBy('slot_number', 'asc')
            ->get();

        $vehicles = $request->user()->vehicles()->where('status', 'active')->get();

        return view('user.zone-slots', compact('location', 'zone', 'slots', 'vehicles'));
    }
}
