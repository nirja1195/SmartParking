<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Requirement #36: Admin Vehicles Management (/admin/vehicles).
     */
    public function index(Request $request): View
    {
        $query = Vehicle::with(['user'])->withCount('bookings')->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('vehicle_model', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($type = $request->input('type')) {
            $query->where('vehicle_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $vehicles = $query->paginate(15)->withQueryString();
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('admin.vehicles.index', compact('vehicles', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'vehicle_number' => ['required', 'string', 'max:30', 'unique:vehicles,vehicle_number'],
            'vehicle_type' => ['required', 'in:Car,Bike,Auto,Bus,Truck,EV'],
            'brand' => ['nullable', 'string', 'max:60'],
            'vehicle_model' => ['nullable', 'string', 'max:60'],
            'vehicle_color' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['vehicle_number'] = strtoupper(trim($validated['vehicle_number']));

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', "Vehicle {$validated['vehicle_number']} registered successfully.");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'vehicle_number' => ['required', 'string', 'max:30', Rule::unique('vehicles')->ignore($vehicle->id)],
            'vehicle_type' => ['required', 'in:Car,Bike,Auto,Bus,Truck,EV'],
            'brand' => ['nullable', 'string', 'max:60'],
            'vehicle_model' => ['nullable', 'string', 'max:60'],
            'vehicle_color' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['vehicle_number'] = strtoupper(trim($validated['vehicle_number']));

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')
            ->with('success', "Vehicle {$vehicle->vehicle_number} details updated.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $vehicle = Vehicle::withCount(['bookings as active_bookings_count' => function ($q) {
            $q->whereIn('booking_status', ['CONFIRMED', 'ACTIVE']);
        }])->findOrFail($id);

        if ($vehicle->active_bookings_count > 0) {
            return back()->with('error', 'Cannot delete this vehicle because it has an active or confirmed reservation.');
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle record removed successfully.');
    }
}
