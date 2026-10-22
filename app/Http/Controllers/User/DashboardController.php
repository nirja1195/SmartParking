<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingLocation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Active Parking (vehicle checked in and currently parked)
        $activeParking = Booking::where('user_id', $user->id)
            ->where('booking_status', Booking::STATUS_ACTIVE)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle', 'parkingEntry'])
            ->latest('entry_time')
            ->first();

        // Upcoming Booking (confirmed reservation awaiting entry)
        $upcomingBooking = Booking::where('user_id', $user->id)
            ->where('booking_status', Booking::STATUS_CONFIRMED)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle'])
            ->where('entry_time', '>=', now()->subHours(2))
            ->orderBy('entry_time', 'asc')
            ->first();

        // Available Parking Locations from MySQL
        $availableLocations = ParkingLocation::where('status', 'Active')
            ->withCount(['slots as available_slots_count' => fn ($q) => $q->where('status', 'AVAILABLE')])
            ->limit(4)
            ->get();

        // My Vehicles
        $vehicles = $user->vehicles()->get();

        // Recent Bookings
        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['parkingLocation', 'slot', 'vehicle'])
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'activeParking',
            'upcomingBooking',
            'availableLocations',
            'vehicles',
            'recentBookings',
            'notifications'
        ));
    }
}
