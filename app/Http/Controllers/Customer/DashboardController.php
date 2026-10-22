<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingLot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $activeBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
            ->with(['spot.lot', 'vehicle', 'payment'])
            ->latest('start_time')
            ->first();

        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['spot.lot', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'total_vehicles' => $user->vehicles()->count(),
            'total_spent' => Booking::where('user_id', $user->id)->where('status', Booking::STATUS_COMPLETED)->sum('total_amount'),
        ];

        $availableLots = ParkingLot::where('is_active', true)->withCount([
            'spots as available_spots_count' => fn ($q) => $q->where('is_active', true)->where('is_occupied', false),
        ])->get();

        return view('customer.dashboard', compact('stats', 'activeBooking', 'recentBookings', 'availableLots'));
    }
}
