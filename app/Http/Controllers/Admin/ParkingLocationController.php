<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ParkingLocationController extends Controller
{
    /**
     * Requirement #2 & #24: Parking Locations Management Page (/admin/locations).
     */
    public function index(Request $request): View
    {
        // 1. Calculate dynamic statistics cards from real MySQL queries
        $totalLocations = ParkingLocation::count();
        $activeLocations = ParkingLocation::where('status', 'Active')->count();
        $inactiveLocations = ParkingLocation::where('status', 'Inactive')->count();

        $totalSlots = ParkingSlot::count();
        $availableSlots = ParkingSlot::where('status', ParkingSlot::STATUS_AVAILABLE)->count();
        $occupiedSlots = ParkingSlot::where('status', ParkingSlot::STATUS_OCCUPIED)->count();

        $stats = [
            'total_locations' => $totalLocations,
            'active_locations' => $activeLocations,
            'inactive_locations' => $inactiveLocations,
            'total_slots' => $totalSlots,
            'available_slots' => $availableSlots,
            'occupied_slots' => $occupiedSlots,
        ];

        // 2. Build filtered locations query with eager-loaded slot counts
        $query = ParkingLocation::withCount([
            'slots as total_slots_count',
            'slots as available_slots_count' => function ($q) {
                $q->where('status', ParkingSlot::STATUS_AVAILABLE);
            },
            'slots as occupied_slots_count' => function ($q) {
                $q->where('status', ParkingSlot::STATUS_OCCUPIED);
            },
        ])->orderBy('id', 'asc');

        // Apply Search Filter
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Apply Status Filter
        if ($request->filled('status') && $request->get('status') !== 'All Status') {
            $query->where('status', $request->get('status'));
        }

        // Apply Location Type Filter
        if ($request->filled('location_type') && $request->get('location_type') !== 'All Location Types') {
            $query->where('location_type', $request->get('location_type'));
        }

        // Paginate results preserving filter parameters
        $perPage = 8;
        $locations = $query->paginate($perPage)->withQueryString();

        // 3. Header notifications
        $unreadNotificationsCount = Notification::where('is_read', false)->count();

        return view('admin.parking-locations.index', compact(
            'locations',
            'stats',
            'unreadNotificationsCount'
        ));
    }

    public function create(): View
    {
        $unreadNotificationsCount = Notification::where('is_read', false)->count();
        return view('admin.parking-locations.create', compact('unreadNotificationsCount'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location_type' => ['nullable', 'string', 'in:Commercial,Airport,Office,Transit,Hospital,Educational,Public'],
            'address' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:15'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'parking_charges' => ['required', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $validated['location_type'] = $validated['location_type'] ?? 'Commercial';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/locations'), $filename);
            $validated['image'] = 'images/locations/' . $filename;
        }

        $location = ParkingLocation::create($validated);

        return redirect()->route('admin.locations.show', $location->id)
            ->with('success', "Parking facility '{$location->name}' created successfully.");
    }

    public function show(int $id): View
    {
        $location = ParkingLocation::with(['zones.slots', 'slots'])->findOrFail($id);

        $unreadNotificationsCount = Notification::where('is_read', false)->count();

        $slotStats = [
            'total' => $location->slots()->count(),
            'available' => $location->slots()->where('status', ParkingSlot::STATUS_AVAILABLE)->count(),
            'reserved' => $location->slots()->where('status', ParkingSlot::STATUS_RESERVED)->count(),
            'occupied' => $location->slots()->where('status', ParkingSlot::STATUS_OCCUPIED)->count(),
            'maintenance' => $location->slots()->where('status', ParkingSlot::STATUS_MAINTENANCE)->count(),
            'blocked' => $location->slots()->where('status', ParkingSlot::STATUS_BLOCKED)->count(),
        ];

        return view('admin.parking-locations.show', compact('location', 'slotStats', 'unreadNotificationsCount'));
    }

    public function edit(int $id): View
    {
        $location = ParkingLocation::findOrFail($id);
        $unreadNotificationsCount = Notification::where('is_read', false)->count();

        return view('admin.parking-locations.edit', compact('location', 'unreadNotificationsCount'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $location = ParkingLocation::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location_type' => ['nullable', 'string', 'in:Commercial,Airport,Office,Transit,Hospital,Educational,Public'],
            'address' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:15'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'parking_charges' => ['required', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $validated['location_type'] = $validated['location_type'] ?? ($location->location_type ?: 'Commercial');

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/locations'), $filename);
            $validated['image'] = 'images/locations/' . $filename;
        }

        $location->update($validated);

        return redirect()->route('admin.locations.index')
            ->with('success', "Parking facility '{$location->name}' updated successfully.");
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $location = ParkingLocation::findOrFail($id);
        $newStatus = $location->status === 'Active' ? 'Inactive' : 'Active';
        $location->update(['status' => $newStatus]);

        return back()->with('success', "Location '{$location->name}' status updated to {$newStatus}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $location = ParkingLocation::findOrFail($id);

        // Safety check: Prevent deletion if active or historical bookings exist
        $activeBookings = Booking::where('parking_location_id', $location->id)
            ->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', "Cannot delete location '{$location->name}' because it has {$activeBookings} active booking(s). Deactivate the location instead.");
        }

        // Safe soft-deactivation or full deletion
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', "Parking facility '{$location->name}' removed successfully.");
    }
}
