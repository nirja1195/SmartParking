<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FindParkingController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function index(Request $request): View
    {
        $filters = [
            'city' => $request->input('city'),
            'location' => $request->input('location'),
            'vehicle_type' => $request->input('vehicle_type'),
            'date' => $request->input('date', date('Y-m-d')),
            'entry_time' => $request->input('entry_time'),
            'exit_time' => $request->input('exit_time'),
        ];

        $locations = $this->parkingService->getActiveLocations($filters);

        return view('user.find-parking', compact('locations', 'filters'));
    }
}
