<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingLocation;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Requirement #43: Reports (/admin/reports).
     */
    public function index(Request $request): View
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', Carbon::today()->subDays(7)->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $locationId = $request->input('location_id');

        $query = Booking::with(['user', 'parkingLocation', 'slot', 'vehicle', 'payment', 'parkingEntry', 'parkingExit'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($locationId) {
            $query->where('parking_location_id', $locationId);
        }

        $records = $query->orderBy('booking_date', 'desc')->paginate(20)->withQueryString();

        $summary = [
            'total_bookings' => $query->count(),
            'completed' => (clone $query)->where('booking_status', 'COMPLETED')->count(),
            'active' => (clone $query)->where('booking_status', 'ACTIVE')->count(),
            'cancelled' => (clone $query)->where('booking_status', 'CANCELLED')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'PAID')->sum('amount'),
        ];

        $locations = ParkingLocation::where('status', 'Active')->get();

        return view('admin.reports.index', compact('records', 'summary', 'type', 'startDate', 'endDate', 'locations', 'locationId'));
    }

    public function bookings(Request $request): View
    {
        $request->merge(['type' => 'bookings']);
        return $this->index($request);
    }

    public function revenue(Request $request): View
    {
        $request->merge(['type' => 'revenue']);
        return $this->index($request);
    }

    public function occupancy(Request $request): View
    {
        $request->merge(['type' => 'occupancy']);
        return $this->index($request);
    }

    public function parking(Request $request): View
    {
        $request->merge(['type' => 'parking']);
        return $this->index($request);
    }
}
