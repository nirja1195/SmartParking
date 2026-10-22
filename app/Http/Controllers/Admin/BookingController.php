<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingLocation;
use App\Models\ParkingZone;
use App\Services\BookingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Requirement #34: Admin Booking Management (/admin/bookings).
     */
    public function index(Request $request): View
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'location_id' => ['nullable', 'integer', 'exists:parking_locations,id'],
            'booking_status' => ['nullable', 'in:PENDING,CONFIRMED,ACTIVE,COMPLETED,CANCELLED'],
            'date_from' => ['nullable', 'date'],
            'date_to' => array_filter(['nullable', 'date', $request->filled('date_from') ? 'after_or_equal:date_from' : null]),
        ]);
        $query = Booking::with(['user', 'parkingLocation', 'zone', 'slot', 'vehicle', 'payment'])
            ->orderBy('id', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('vehicle', fn ($v) => $v->where('vehicle_number', 'like', "%{$search}%"));
            });
        }

        if ($filter = $request->input('filter')) {
            if ($filter === 'today') {
                $query->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($filter === 'active') {
                $query->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE]);
            } elseif ($filter === 'history') {
                $query->whereIn('booking_status', [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED]);
            }
        }

        if ($locationId = $request->input('location_id')) {
            $query->where('parking_location_id', $locationId);
        }

        if ($bookingStatus = $request->input('booking_status')) {
            $query->where('booking_status', $bookingStatus);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($date = $request->input('date')) {
            $query->whereDate('booking_date', $date);
        }

        if ($request->filled('date_from')) $query->whereDate('booking_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('booking_date', '<=', $request->date_to);
        $metrics = [
            ['label' => 'Total Bookings', 'icon' => 'calendar2-week', 'tone' => 'gold', 'status' => null],
            ['label' => 'Active Bookings', 'icon' => 'check-circle-fill', 'tone' => 'green', 'status' => Booking::STATUS_ACTIVE],
            ['label' => 'Upcoming Bookings', 'icon' => 'clock', 'tone' => 'gold', 'status' => Booking::STATUS_CONFIRMED],
            ['label' => 'Completed Bookings', 'icon' => 'check2-circle', 'tone' => 'red', 'status' => Booking::STATUS_COMPLETED],
        ];
        foreach ($metrics as &$metric) {
            $base = Booking::query();
            if ($request->filled('location_id')) $base->where('parking_location_id', $request->location_id);
            if ($metric['status']) $base->where('booking_status', $metric['status']);
            $metric['count'] = (clone $base)->count();
            $current = (clone $base)->whereBetween('created_at', [now()->startOfMonth(), now()])->count();
            $previous = (clone $base)->whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])->count();
            $metric['change'] = $previous ? round(($current - $previous) / $previous * 100) : null;
        }
        unset($metric);
        $bookings = $query->paginate(10)->withQueryString();
        $availableSlots = \App\Models\ParkingSlot::with('parkingLocation', 'zone')->where('status', 'AVAILABLE')->whereHas('parkingLocation', fn($q) => $q->where('status', 'Active'))->get();
        $vehicles = \App\Models\Vehicle::with('user')->orderBy('vehicle_number')->get();
        $locations = ParkingLocation::where('status', 'Active')->get();

        return view('admin.bookings.index', compact('bookings', 'locations', 'metrics', 'availableSlots', 'vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slot_id' => ['required', 'integer', 'exists:parking_slots,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'entry_time' => ['required', 'date', 'after_or_equal:now'],
            'expected_exit_time' => ['required', 'date', 'after:entry_time'],
        ]);
        $slot = \App\Models\ParkingSlot::with('parkingLocation', 'zone')->findOrFail($data['slot_id']);
        $vehicle = \App\Models\Vehicle::findOrFail($data['vehicle_id']);
        if (!$slot->zone || $slot->parkingLocation?->status !== 'Active' || !$vehicle->user_id) {
            return back()->withInput()->withErrors(['booking' => 'Choose a vehicle with an owner and a slot at an active location.']);
        }
        try {
            $booking = $this->bookingService->createBooking(array_merge($data, [
                'user_id' => $vehicle->user_id, 'parking_location_id' => $slot->parking_location_id,
                'zone_id' => $slot->zone_id, 'booking_date' => \Carbon\Carbon::parse($data['entry_time'])->toDateString(),
            ]));
            return redirect()->route('admin.bookings.index')->with('success', "Booking #{$booking->booking_id} created. Payment is pending.");
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['booking' => $e->getMessage()]);
        }
    }
    public function show(int $id): View
    {
        $booking = Booking::with(['user', 'parkingLocation', 'zone', 'slot', 'vehicle', 'payment', 'parkingEntry', 'parkingExit'])
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $this->bookingService->cancelBooking($booking->id, $booking->user_id);

            return back()->with('success', "Booking #{$booking->booking_id} cancelled.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
