<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Requirement #41: Analytics (/admin/analytics).
     * Computes real MySQL data for Chart.js.
     */
    public function index(): View
    {
        // 1. Daily Bookings (Last 7 days)
        $dailyBookings = Booking::select(DB::raw('DATE(booking_date) as date'), DB::raw('count(*) as total'))
            ->where('booking_date', '>=', Carbon::today()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 2. Revenue Trend (Last 6 months)
        $monthlyRevenue = Payment::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('sum(amount) as revenue'))
            ->where('status', Payment::STATUS_SUCCESS)
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 3. Vehicle Type Breakdown
        $vehicleTypeStats = Booking::select('vehicles.vehicle_type', DB::raw('count(*) as count'))
            ->leftJoin('vehicles', 'bookings.vehicle_id', '=', 'vehicles.id')
            ->groupBy('vehicles.vehicle_type')
            ->get();

        // 4. Entries vs Exits Today
        $todayEntriesCount = ParkingEntry::whereDate('entry_time', Carbon::today())->count();
        $todayExitsCount = ParkingExit::whereDate('exit_time', Carbon::today())->count();

        // 5. Overall Slot Status distribution
        $slotDistribution = [
            'available' => ParkingSlot::where('status', 'AVAILABLE')->count(),
            'reserved' => ParkingSlot::where('status', 'RESERVED')->count(),
            'occupied' => ParkingSlot::where('status', 'OCCUPIED')->count(),
            'maintenance' => ParkingSlot::where('status', 'MAINTENANCE')->count(),
            'blocked' => ParkingSlot::where('status', 'BLOCKED')->count(),
        ];

        // 6. Popular Locations
        $popularLocations = ParkingLocation::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.analytics.index', compact(
            'dailyBookings',
            'monthlyRevenue',
            'vehicleTypeStats',
            'todayEntriesCount',
            'todayExitsCount',
            'slotDistribution',
            'popularLocations'
        ));
    }
}
