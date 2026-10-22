<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $vehicles = $request->user()->vehicles()->withCount('bookings')->get();

        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['license_plate'] = strtoupper(trim($data['license_plate']));

        Vehicle::create($data);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Vehicle registered successfully.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $vehicle = Vehicle::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $hasActiveBookings = $vehicle->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot remove vehicle with active reservations.');
        }

        $vehicle->delete();

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Vehicle removed from your account.');
    }
}
