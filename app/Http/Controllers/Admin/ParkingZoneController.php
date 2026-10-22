<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\ParkingZone;
use App\Services\ParkingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParkingZoneController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    /**
     * Requirement #15: Parking Zones.
     */
    public function index(Request $request): View
    {
        $query = ParkingZone::with(['parkingLocation', 'slots'])->withCount('slots');

        if ($locationId = $request->input('location_id')) {
            $query->where('parking_location_id', $locationId);
        }

        $zones = $query->orderBy('parking_location_id')->paginate(15);
        $locations = ParkingLocation::where('status', 'Active')->get();

        return view('admin.parking-zones.index', compact('zones', 'locations'));
    }

    public function create(Request $request): View
    {
        $locations = ParkingLocation::where('status', 'Active')->get();
        $selectedLocationId = $request->query('location_id');

        return view('admin.parking-zones.create', compact('locations', 'selectedLocationId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'parking_location_id' => ['required', 'exists:parking_locations,id'],
            'zone_name' => ['required', 'string', 'max:100'],
            'zone_code' => ['nullable', 'string', 'max:50'],
            'vehicle_type' => ['required', 'in:Car,Bike,Auto,Bus,Truck,EV'],
            'floor' => ['required', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $zone = $this->parkingService->createZone($data);

        return redirect()->route('admin.parking-slots.index', ['zone_id' => $zone->id])
            ->with('success', "Zone '{$zone->zone_name}' created. Now create parking slots.");
    }

    public function edit(int $id): View
    {
        $zone = ParkingZone::findOrFail($id);
        $locations = ParkingLocation::where('status', 'Active')->get();

        return view('admin.parking-zones.edit', compact('zone', 'locations'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'parking_location_id' => ['required', 'exists:parking_locations,id'],
            'zone_name' => ['required', 'string', 'max:100'],
            'zone_code' => ['nullable', 'string', 'max:50'],
            'vehicle_type' => ['required', 'in:Car,Bike,Auto,Bus,Truck,EV'],
            'floor' => ['required', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $this->parkingService->updateZone($id, $data);

        return redirect()->route('admin.parking-zones.index')
            ->with('success', 'Parking zone updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->parkingService->deleteZone($id);

        return redirect()->route('admin.parking-zones.index')
            ->with('success', 'Parking zone and its slots removed.');
    }
}
