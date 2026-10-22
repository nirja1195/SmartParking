<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    /**
     * Requirement #7: Staff Terminal Dashboard (/staff/dashboard).
     */
    public function index(): View
    {
        $staff = Auth::guard('staff')->user();
        $today = Carbon::today();

        $query = Booking::query();
        if ($staff && $staff->parking_location_id) {
            $query->where('parking_location_id', $staff->parking_location_id);
        }

        $activeParkedCount = (clone $query)->where('booking_status', 'ACTIVE')->count();
        $todayConfirmedCount = (clone $query)->where('booking_status', 'CONFIRMED')->whereDate('booking_date', $today)->count();
        $todayEntriesCount = ParkingEntry::whereDate('entry_time', $today)->count();
        $todayExitsCount = ParkingExit::whereDate('exit_time', $today)->count();

        $recentEntries = ParkingEntry::with(['booking.user', 'vehicle', 'slot.parkingLocation'])
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return view('staff.dashboard', compact(
            'staff',
            'activeParkedCount',
            'todayConfirmedCount',
            'todayEntriesCount',
            'todayExitsCount',
            'recentEntries'
        ));
    }
}
