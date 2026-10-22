<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Requirement #9 & #13: Fully Dynamic SMARTPARK AI Admin Dashboard (/admin/dashboard).
     */
    public function index(Request $request): View
    {
        $selectedPeriod = $request->get('period', '7_days');
        $selectedDateInput = $request->get('date');

        if ($selectedDateInput) {
            $selectedDate = Carbon::parse($selectedDateInput)->startOfDay();
        } else {
            $selectedDate = Carbon::today();
        }

        // Period range calculation for period-over-period percentage comparisons
        $days = match ($selectedPeriod) {
            '30_days' => 30,
            'this_month' => Carbon::now()->day,
            default => 7,
        };

        $currentStart = (clone $selectedDate)->subDays($days - 1)->startOfDay();
        $currentEnd = (clone $selectedDate)->endOfDay();
        $prevStart = (clone $currentStart)->subDays($days);
        $prevEnd = (clone $currentStart)->subSecond();

        // 1. Total Vehicles & Comparison
        $totalVehicles = Vehicle::count();
        $currentVehiclesCount = Vehicle::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $prevVehiclesCount = Vehicle::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $vehiclesPct = $this->calculatePercentageChange($currentVehiclesCount, $prevVehiclesCount);

        // 2. Total Users & Comparison
        $totalUsers = User::count();
        $currentUsersCount = User::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $prevUsersCount = User::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $usersPct = $this->calculatePercentageChange($currentUsersCount, $prevUsersCount);

        // 3. Occupied Slots & Comparison
        $totalSlotsCount = ParkingSlot::count();
        $occupiedSlotsCount = ParkingSlot::where('status', ParkingSlot::STATUS_OCCUPIED)->count();

        // Historical / Period occupancy comparison based on active bookings during period
        $currentOccupancyCount = Booking::where('booking_status', Booking::STATUS_ACTIVE)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $prevOccupancyCount = Booking::where('booking_status', Booking::STATUS_ACTIVE)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();
        $occupiedPct = $this->calculatePercentageChange($currentOccupancyCount, $prevOccupancyCount);

        // 4. Total Revenue & Period Comparison (ONLY STATUS_SUCCESS)
        $totalRevenue = Payment::where('status', Payment::STATUS_SUCCESS)->sum('amount');
        $currentRevenue = Payment::where('status', Payment::STATUS_SUCCESS)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('amount');
        $prevRevenue = Payment::where('status', Payment::STATUS_SUCCESS)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('amount');
        $revenuePct = $this->calculatePercentageChange($currentRevenue, $prevRevenue);

        $stats = [
            'total_vehicles' => $totalVehicles,
            'vehicles_pct' => $vehiclesPct,
            'total_users' => $totalUsers,
            'users_pct' => $usersPct,
            'occupied_slots' => $occupiedSlotsCount,
            'occupied_pct' => $occupiedPct,
            'total_revenue' => $totalRevenue,
            'revenue_pct' => $revenuePct,
        ];

        // 5. Parking Activity Chart (Entries vs Exits aggregated by date)
        $chartLabels = [];
        $entriesData = [];
        $exitsData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = (clone $selectedDate)->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $labelStr = $date->format('M d');

            $chartLabels[] = $labelStr;

            $entriesCount = ParkingEntry::whereDate('entry_time', $dateStr)->count();
            $exitsCount = ParkingExit::whereDate('exit_time', $dateStr)->count();

            $entriesData[] = $entriesCount;
            $exitsData[] = $exitsCount;
        }

        $activityChart = [
            'labels' => $chartLabels,
            'entries' => $entriesData,
            'exits' => $exitsData,
        ];

        // 6. Slot Status Donut Distribution (Reconciles 100% with Total Slots)
        $slotDistribution = [
            'available' => ParkingSlot::where('status', ParkingSlot::STATUS_AVAILABLE)->count(),
            'occupied' => ParkingSlot::where('status', ParkingSlot::STATUS_OCCUPIED)->count(),
            'maintenance' => ParkingSlot::where('status', ParkingSlot::STATUS_MAINTENANCE)->count(),
            'reserved' => ParkingSlot::where('status', ParkingSlot::STATUS_RESERVED)->count(),
            'blocked' => ParkingSlot::where('status', ParkingSlot::STATUS_BLOCKED)->count(),
            'total' => $totalSlotsCount,
        ];

        // 7. Recent Bookings Table (Eager loaded relationships, limit 5)
        $recentBookings = Booking::with(['user', 'vehicle', 'parkingLocation', 'parkingEntry', 'parkingExit'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 8. Header Notifications
        $unreadNotificationsCount = Notification::where('is_read', false)->count();
        $recentNotifications = Notification::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'activityChart',
            'slotDistribution',
            'recentBookings',
            'unreadNotificationsCount',
            'recentNotifications',
            'selectedPeriod',
            'selectedDate'
        ));
    }

    /**
     * Helper to safely calculate period-over-period percentage change.
     */
    private function calculatePercentageChange(float|int $current, float|int $previous): array
    {
        if ($previous == 0) {
            if ($current > 0) {
                return ['val' => 100, 'formatted' => '+100%', 'is_positive' => true, 'is_neutral' => false];
            }
            return ['val' => 0, 'formatted' => '0%', 'is_positive' => true, 'is_neutral' => true];
        }

        $change = (($current - $previous) / $previous) * 100;
        $rounded = round($change);

        if ($rounded > 0) {
            return ['val' => $rounded, 'formatted' => '+' . $rounded . '%', 'is_positive' => true, 'is_neutral' => false];
        } elseif ($rounded < 0) {
            return ['val' => abs($rounded), 'formatted' => $rounded . '%', 'is_positive' => false, 'is_neutral' => false];
        } else {
            return ['val' => 0, 'formatted' => '0%', 'is_positive' => true, 'is_neutral' => true];
        }
    }
}
