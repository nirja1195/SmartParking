<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Requirement #36: User Vehicles (/my-vehicles).
     */
    public function index(Request $request): View
    {
        $vehicles = $request->user()->vehicles()->withCount('bookings')->get();

        return view('user.vehicles', compact('vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vehicle_number' => ['required', 'string', 'max:30', 'unique:vehicles,vehicle_number'],
            'vehicle_type' => ['required', 'in:Car,Bike,Auto,Bus,Truck,EV'],
            'vehicle_model' => ['nullable', 'string', 'max:50'],
            'vehicle_color' => ['nullable', 'string', 'max:30'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $userId = $request->user()->id;
        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            Vehicle::where('user_id', $userId)->update(['is_default' => false]);
        }

        Vehicle::create([
            'user_id' => $userId,
            'vehicle_number' => strtoupper(trim($request->input('vehicle_number'))),
            'vehicle_type' => $request->input('vehicle_type'),
            'vehicle_model' => $request->input('vehicle_model'),
            'vehicle_color' => $request->input('vehicle_color'),
            'is_default' => $isDefault,
            'status' => 'active',
        ]);

        return redirect()->route('user.vehicles')
            ->with('success', 'Vehicle added successfully.');
    }

    public function setDefault(int $id, Request $request): RedirectResponse
    {
        $userId = $request->user()->id;

        Vehicle::where('user_id', $userId)->update(['is_default' => false]);
        Vehicle::where('id', $id)->where('user_id', $userId)->update(['is_default' => true]);

        return back()->with('success', 'Default vehicle updated.');
    }

    public function destroy(int $id, Request $request): RedirectResponse
    {
        $vehicle = Vehicle::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $hasActiveBookings = $vehicle->bookings()
            ->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot remove vehicle while it has active parking sessions.');
        }

        $vehicle->delete();

        return redirect()->route('user.vehicles')
            ->with('success', 'Vehicle removed from your garage.');
    }
}
