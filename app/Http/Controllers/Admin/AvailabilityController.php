<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    /**
     * Requirement #33: Parking Availability Monitor (/admin/availability).
     */
    public function index(Request $request): View
    {
        $globalStats = $this->parkingService->getGlobalSlotStats();

        $locations = ParkingLocation::where('status', 'Active')
            ->with(['zones.slots'])
            ->get();

        return view('admin.availability.index', compact('globalStats', 'locations'));
    }
}
